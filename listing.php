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

$result = query($query);
$row = $result->fetch_assoc();

$query2 = "
SELECT u.country 
FROM users u
JOIN auctions a
ON a.sellerEmail = u.email
WHERE a.auctionID = {$item_id} AND u.accountType = 'seller'";
$result2 = query($query2);
$row2 = $result2->fetch_assoc();

$query = "SELECT *
from watching 
where auctionID = {$item_id}";
$watching_result = query($query);
$num_watching = mysqli_num_rows($watching_result);



// Set variables based on query
$title = $row['title'];
$description = $row['description'];
$num_bids = $row['num_bids'];
$current_price = $row['current_price'];
$end_time = new DateTime($row['endDate']);
$image = $row['picture'];
$country = $row2['country'];

// TODO: Note: Auctions that have ended may pull a different set of data,
//       like whether the auction ended in a sale or was cancelled due
//       to lack of high-enough bids. Or maybe not.

// Calculate time to auction end:
$now = new DateTime();

if ($now < $end_time) {
  $time_to_end = date_diff($now, $end_time);
  $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
}

// TODO: If the user has a session, use it to make a query to the database
//       to determine if the user is already watching this item.
//       For now, this is hardcoded.
if(!isset($_SESSION['account_type']) || $_SESSION['account_type'] == "seller" ){
  $has_session = false;
}
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
  // if($id) { // ? this would only be false if there was an error - in which case the query is WRONG
  //   $num = mysqli_num_rows($id);}
  // else {$num = 0;}
  // if($num>=1){
  //   $watching = True;
  // }
  // else{
  //   $watching = False;
  // }
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
  if (isset($_SESSION['account_type'])){
  
  echo("<br>");
  echo("<p> <b> Previous Bids: </b> </p>");
  
  $query3 = "SELECT bidValue, bidDate, buyerEmail
  FROM bids
  WHERE auctionID = {$item_id}
  ORDER BY bidValue DESC";

  $result3 = query($query3);
  $counter = 0;
  if (mysqli_num_rows($result3)==0) { 
    echo("<p>There have been no bids placed so far.</p>");
   }
  while($row3 = $result3 -> fetch_assoc() and $counter < 10){
    $bid_value = $row3['bidValue'];
    $bid_date = $row3['bidDate'];
    $buyer_email = $row3['buyerEmail'];
    $counter += 1;
    if ($buyer_email == $email){
      echo("<p>You placed a £{$bid_value} bid at: {$bid_date}</p>");
    }else{
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