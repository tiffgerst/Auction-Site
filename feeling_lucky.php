<?php
include_once("utilities.php");

session_start();

// Randomnly order the auctions and take the first result
$buyeremail = $_SESSION['username'];
$random_listing_query = "SELECT auctionID FROM auctions
WHERE endDate > CURRENT_TIME() AND auctionID NOT IN 
(SELECT auctionID from bids WHERE buyerEmail = \"$buyeremail\" )
ORDER BY RAND()
LIMIT 1";

$result = query($random_listing_query);

if (mysqli_num_rows($result)==0) {
    echo("You're already bidding on all live auctions! :) Redirecting you to browse now!");
    header("refresh:3;url=browse.php");
    exit;
}

$result = $result->fetch_assoc();
$random_item = $result["auctionID"];

// Redirect
$randomURL = "listing.php?item_id='$random_item'";
header('Location: '.$randomURL);
?>