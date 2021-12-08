<?php include_once("utilities.php")?>
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

    if ($bidValue <= round($highestBid*1.05,2)) {
      exit;
    }
}

else {
  // Use the start price to set minimum bid amount
  $startPrice = query("SELECT startPrice FROM auctions WHERE auctionID = $auctionID")->fetch_assoc()['startPrice'];
  
  if ($bidValue <= round($startPrice*1.05,2)) {
    exit;
  }
}

// Generate bid date
$bidDate = date('Y-m-d H:i:s');

// INSERT
$query = "INSERT INTO bids (buyerEmail, auctionID, bidValue, bidDate) VALUES ('$buyerEmail',$auctionID,$bidValue,'$bidDate')";
query($query);

$query = "INSERT INTO watching (auctionID, buyerEmail)
VALUES('$auctionID', '$buyerEmail')";
query($query);

// Notify and redirect
echo('<div class="text-center">Bid placed successfully! You will be redirected shortly</div>');
header('refresh:5;url="listing.php?item_id='.$auctionID.'"');
?>