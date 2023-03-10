<?php include_once("utilities.php");?>
<?php
session_start();

// Make sure the user is logged in and is a buyer
if ((!isset($_SESSION['username']))||($_SESSION['account_type'] != 'buyer')) {
  // Give the option to be redirected to register.php
  echo('<p> Please <a href=register.php>sign in</a> as a buyer to place bids </p>');
  // Redirect to the listing
  header("refresh:3;url=listing.php?item_id=".$_GET['item_id']);
  exit;
}

function check($data) {
  if (!isset($data)) {
      echo('Please fill out all required entries');
      exit;
  }
  else{
      return $data;
  }
}

// Validate POST
$bidValue = check($_POST['bid']);
$auctionID = check($_GET['item_id']);
$buyerEmail = $_SESSION['username'];

if ((!is_numeric($bidValue))||(!is_numeric($auctionID))){
  exit;
}

// Check to see if the user placing a new bid is already the highest bidder
$query = "SELECT buyerEmail, MAX(bidValue) AS 'maxBid'
FROM bids
WHERE auctionID = $auctionID
GROUP BY buyerEmail
ORDER BY maxBid DESC";
$initialResult = query($query);

if (mysqli_num_rows($initialResult)>0){
    $row =  $initialResult -> fetch_assoc();
    $highestBid = $row["maxBid"];
    $highbid = $row["buyerEmail"];
    if ($bidValue < round($highestBid*1.05,2)) {
      exit;
    }
}

else {
  // Use the start price to set minimum bid amount
  $startPrice = query("SELECT startPrice FROM auctions WHERE auctionID = $auctionID")->fetch_assoc()['startPrice'];
  
  if ($bidValue < round($startPrice*1.05,2)) {
    exit;
  }
}

// Everything is OK -> write some emails

// Get the title of the auction
$title = query("SELECT title FROM auctions where auctionID = $auctionID")->fetch_assoc()['title'];

/* 
"Someone has bid on X that you are watching" email
*/

// Find out the watchers that we need to email
$query = "SELECT buyerEmail FROM watching where auctionID = $auctionID AND buyerEmail <> '$buyerEmail'";

// If there is already a highest bidder, we will send them a different email
// So we don't want them on this email list
if (isset($highbid)) {
  $query .= " AND buyerEmail<>'$highbid'";
}

$result = query($query);
$num_results = mysqli_num_rows($result);
if ($num_results != 0) {
  $body = "Someone has placed a bid on an item you are watching: $title. The current highest bid is now: $bidValue";
  while ($row = $result->fetch_assoc()) {
    $mail = $row['buyerEmail'];
    send_email($mail,"Bid placed",$body);
  }}

/* 
"You've been outbid" email
*/

// If there is an existing highest bidder, and it's not the current bidder
// Send the old highest bidder an email
if ((isset($highbid)) && ($highbid!=$buyerEmail)) {
  $body = "You have been outbid! Someone has bid more than you on: $title. The current highest bid is now: $bidValue ";
  send_email($highbid,"You've been outbid",$body);
}

// INSERT
$query = "INSERT INTO bids (buyerEmail, auctionID, bidValue, bidDate) VALUES ('$buyerEmail',$auctionID,$bidValue,CURRENT_TIME())";
query($query);

$query = "INSERT INTO watching (auctionID, buyerEmail)
VALUES($auctionID, '$buyerEmail')";
query($query);

// Notify and redirect
echo('<div class="text-center">Bid placed successfully! You will be redirected shortly</div>');
header('refresh:5;url="listing.php?item_id='.$auctionID.'"');
?>