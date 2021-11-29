<?php include_once("header.php")?>
<?php include_once("utilities.php")?>

<?php
// GET item_id
$item_id = $_GET['item_id'];

// Perform query using the item_id
$query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.picture, 
COALESCE(b.current_price,a.startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids' 
FROM
(SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b 
RIGHT JOIN (SELECT * FROM auctions a WHERE a.auctionID = ".$item_id.") a 
ON a.auctionID = b.auctionID";

$row = query($query)->fetch_assoc();
$title = $row['title'];
$description = $row['description'];
$num_bids = $row['num_bids'];
$current_price = $row['current_price'];
$end_time = new DateTime($row['endDate']);
$image = $row['picture'];

// Get the country
$query = "
SELECT u.country 
FROM users u
JOIN auctions a
ON a.sellerEmail = u.email
WHERE a.auctionID = {$item_id} AND u.accountType = 'seller'";
$country = query($query)->fetch_assoc()['country'];

// Get the number of watchers
$query = "SELECT *
FROM watching 
WHERE auctionID = {$item_id}";
$num_watching = mysqli_num_rows(query($query));

// TODO: Note: Auctions that have ended may pull a different set of data,
//       like whether the auction ended in a sale or was cancelled due
//       to lack of high-enough bids. Or maybe not.

// Calculate time to auction end:
$now = new DateTime();

if ($now < $end_time) {
  $time_to_end = date_diff($now, $end_time);
  $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
}

// User does not have a session or is a seller
if(!isset($_SESSION['account_type']) || $_SESSION['account_type'] == "seller" ){
  $has_session = false;
}
// Use is a buyer -> check if they're watching the item
else{
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
?>

<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($title); ?></h2>
    <p>Item shipped from <?php echo($country)?>.</p>
  </div>

  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if ($now < $end_time):
?>
    <div id="watch_nowatch" <?php if (!$has_session || $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
    </div>
<?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->

    <div class="itemDescription">
    <img src="images/<?php echo $image; ?>" width = "600px"> <br><br>
    <?php echo($description); ?>
    </div>

  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->
    <?php
    if ($num_watching == 1){
      echo("<p><b>1</b> user is watching the auction</p>");
    }else{
      echo("<p><b>{$num_watching}</b> users are watching the auction.</p>");
    }
    ?>
    <p>
<?php if ($now > $end_time): ?>
     This auction ended <?php echo(date_format($end_time, 'j M H:i')) ?>
     <!-- TODO: Print the result of the auction here? -->
<?php else: ?>
     Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
    <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

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
          <span class="input-group-text">£</span>
        </div>
	    <input type="number" class="form-control" name="bid" id="bid" step="0.01" min="<?php echo(number_format(round($current_price*1.05,2),2))?>">
      </div>
      <small id="bidHelp" class="form-text text-muted" <?php if ($noWatch) echo('style="display: none"');?>> <?php echo("Enter £".number_format(round($current_price*1.05,2),2)." or more")?> </small>
      <button type="submit" class="btn btn-primary form-control"<?php if ($noWatch) echo('style="display: none"');?>>Place bid</button>
    </form>
<?php endif ?>
<?php 
  // Bidding history
  if (isset($_SESSION['account_type'])){
  
  echo("<br>");
  echo("<p> <b> Previous Bids: </b> </p>");
  
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
    if ($buyer_email == $email){
      echo("<p>You placed a £{$bid_value} bid at: {$bid_date}</p>");
    }
    else {
      echo("<p>Bid with a value of £{$bid_value} placed at: {$bid_date}</p>");
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