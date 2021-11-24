<?php include_once("utilities.php")?>
<?php session_start()?>
<?php

$bidValue = $_POST['bid']; # All bid value verification done via .js
$buyerEmail = $_SESSION['username'];
$auctionID = $_GET['item_id'];


$query = "SELECT buyerEmail
FROM bids
WHERE auctionID = ".$auctionID."
AND bidValue = (SELECT MAX(bidValue) FROM bids WHERE auctionID = ".$auctionID.")";

$initialResult = query($query);
$number_of_bids = mysqli_num_rows($initialResult);
if ($number_of_bids > 0){
    $initialResult =  $initialResult -> fetch_assoc();
    $email = $initialResult["buyerEmail"];
}else{
    $email = "Wrong Email";
}

if ($email == $buyerEmail){
echo('<div class="text-center">You are already the highest bidder! Your bid has not been registered and you will be redirected shorty.</div>');
header('refresh:5;url="listing.php?item_id='.$auctionID.'"');
}else{



# Generate a bid ID by querying the database
$result = query("SELECT bidID FROM bids");
$numrows = mysqli_num_rows($result);
$bidID = $numrows;

# INSERT
$query = sprintf("INSERT INTO bids VALUES (%g,'%s',%g,%g)",
    $bidID,
    $buyerEmail,
    $auctionID,
    $bidValue);

query($query);

# Notify and redirect
echo('<div class="text-center">Bid placed successfully! You will be redirected shortly</div>');
header('refresh:5;url="listing.php?item_id='.$auctionID.'"');
}
?>