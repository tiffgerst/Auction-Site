<?php include_once("header.php")?>
<?php include_once("utilities.php")?>

<?php
// GET item_id
$item_id = $_GET['item_id'];

// Perform query using the item_id
$query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.picture, a.reservePrice, a.startPrice, a.country,
COALESCE(b.current_price,a.startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids'  
FROM 
(SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b
RIGHT JOIN (
    SELECT a.sellerEmail, a.auctionID, a.title, a.description, a.endDate, a.picture, a.reservePrice, a.startPrice, u.country
    FROM auctions a, users u WHERE u.email = a.sellerEmail AND a.auctionID = $item_id) a  
ON a.auctionID = b.auctionID";

$row = query($query)->fetch_assoc();
$title = $row['title'];
$description = $row['description'];
$num_bids = $row['num_bids'];
$current_price = $row['current_price'];
$end_time = new DateTime($row['endDate']);
$image = $row['picture'];
$reserve_price = $row['reservePrice'];
$start_price = $row['startPrice'];
$country = $row['country'];

$now = new DateTime();

// For non-expired auctions:
if ($now < $end_time) {
  // Get the number of watchers
  $query = "SELECT *
  FROM watching 
  WHERE auctionID = {$item_id}";
  $num_watching = mysqli_num_rows(query($query));

  // Check user information for the purpose of watching functionality
  if(!isset($_SESSION['account_type']) || $_SESSION['account_type'] == "seller" ){
    // User does not have a session or is a seller
    $has_session = false;
  }
  else{
    // User is a buyer -> check if they're watching the item
    $has_session = true;
    $email = $_SESSION["username"];
    $watchingResult = query("SELECT * from watching WHERE buyerEmail='$email'AND auctionID=$item_id");
    if (mysqli_num_rows($watchingResult) != 0) {
      $watching = TRUE;
    }
    else {
      $watching = FALSE;
    }
  }

  // Calculate time to auction end:
  $time_to_end = date_diff($now, $end_time);
  $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
  }

// For expired auctions:
else {
  // Determine the circumstances in which the auction ended
  if ($num_bids == 0) {
    $expiry_circumstances = "Unsuccessful auction - no bids placed";
  }
  else if ($current_price < $reserve_price) {
    $expiry_circumstances = "Unsuccessful auction - bids placed but reserve price not met";
  }
  else {
    $expiry_circumstances = "Successful auction";
  }
} 
?>

<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><b><?php echo($title); ?></b></h2>
    <p class="text-muted"><i>Item shipped from <?php echo($country)?></i></p>
  </div>

  <div class="col-sm-4 align-self-center"> <!-- Right col -->
  <?php if ($now < $end_time && $has_session):?>  
    <?php if (!$watching):?>
      <div id="watch_nowatch">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist(); window.location.reload()">+ Add to watchlist</button>
      </div>
    <?php else:?>
      <div id="watch_watching">
        <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist();window.location.reload()">Remove watch</button>
      </div>
      <?php endif ?>
  <?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->
    <div class="itemDescription">
    <img alt="" src="images/<?php echo $image; ?>" style="max-width:600px;width:100%"> <br><br>
    <?php echo($description); ?>
    </div>
  </div>
  <div class="col-sm-4"> <!-- Right col with bidding info -->
    <?php if ($now > $end_time):?>
      <p><i>This auction ended <?php echo(date_format($end_time, 'j M H:i'))?></i></p>
      <p><?php echo($expiry_circumstances)?></p>
      <?php
        echo("<div style='padding-left:1rem;border-left:#A6C6E3 solid .5rem;background-color:#F5F5F5;padding-top:1rem;padding-bottom:1rem;padding-right:1rem'>");
        if ($expiry_circumstances == "Unsuccessful auction - no bids placed"){
          echo("<h3><b>??$start_price</b></h3>");
          echo("<p style='margin-bottom:0rem'>Start price</p>");
        }
        else {
          echo("<h3><b>??$current_price</b></h3>");
          echo("<p style='margin-bottom:0rem'>Final price</p>");
        }
        echo("</div>")
        ?>
    <?php else:?>
      <?php
      if ($num_watching == 1) {
        echo("<p><b>1</b> user is watching the auction</p>");
      }
      else{
        echo("<p><b>{$num_watching}</b> users are watching the auction.</p>");
      }
      ?>
      <p>Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
      
      <!-- Bidding block -->
      <div style='padding-left:1rem;border-left:#A6C6E3 solid .5rem;background-color:#F5F5F5;padding-top:1rem;padding-bottom:1rem;padding-right:1rem'>
      <p class="lead">Current bid: ??<?php echo(number_format($current_price, 2)) ?></p>

      <!-- Bidding form -->
      <?php if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] == 'seller'){
        $noWatch = true;
      }
      else{
        $noWatch = false;
      }
      ?>
      <form method="POST" id="createNewBid" action=<?php echo("place_bid.php?item_id=".$item_id)?>>
        <div class="input-group" <?php if ($noWatch) echo('style="display: none"');?>>
          <div class="input-group-prepend">
            <span class="input-group-text">??</span>
          </div>
        <input type="number" class="form-control" name="bid" id="bid" step="0.01" min="<?php echo(number_format(round($current_price*1.05,2),2))?>">
        </div>
        <small style="margin-bottom:.5rem" id="bidHelp" class="form-text text-muted" <?php if ($noWatch) echo('style="display: none"');?>> <?php echo("Enter ??".number_format(round($current_price*1.05,2),2)." or more")?> </small>
        <button type="submit" style="border-color:#A6C6E3;background-color:#A6C6E3"class="btn btn-primary form-control"<?php if ($noWatch) echo('style="display: none"');?>><b>Place bid</b></button>
      </form>
    </div>
<?php endif ?>
<?php
  if ($now > $end_time) {
    exit;
  }else{
    $query = "SELECT reservePrice FROM auctions WHERE auctionID = {$item_id}";
    $result = query($query);
    $result = $result->fetch_assoc();
    $reserve_price = $result['reservePrice'];
    if ($reserve_price <= $current_price){
      echo('<h5>The reserve price for this item has been met! <i> BID TO WIN!</i> </h5>');
    }else{
      echo('<h5>The reserve price for this item has <b> NOT </b> been met! <i> BID! BID! BID!</i></h5>');  
    }
    }

  // Bidding history
  if (isset($_SESSION['account_type'])){
  
  echo("<br>");
  echo("<p> <b> Previous Bids </b> </p>");
  
  // Get all the bids for the item ID
  $query = "SELECT bidValue, bidDate, buyerEmail
  FROM bids
  WHERE auctionID = {$item_id}
  ORDER BY bidValue DESC
  LIMIT 10";

  $result = query($query);
  
  if (mysqli_num_rows($result)==0) { 
    echo("<p>There have been no bids placed so far.</p>");
  }

  while ($row = $result->fetch_assoc()){
    $bid_value = $row['bidValue'];
    $bid_date = $row['bidDate'];
    $buyer_email = $row['buyerEmail'];
    
    // Email won't be set if they are a seller
    if ((isset($email))&&($buyer_email == $email)){
      echo("<p>You placed a ??{$bid_value} bid at {$bid_date}</p>");
    }
    else {
      // Everyone who's signed in should be set
      echo("<p>Bid with a value of ??{$bid_value} placed at {$bid_date}</p>");
    }
  }
  }
?>

  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->


<?php include_once("footer.php")?>


<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        console.log(obj);
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>