<?php
include_once("header.php");
include_once("utilities.php");

// Randomnly order the auctions and take the first result
$buyeremail = $_SESSION['username'];
$random_listing_query = "SELECT auctionID FROM auctions
WHERE endDate > CURRENT_TIME() AND auctionID NOT IN 
(SELECT auctionID from bids WHERE buyerEmail = \"$buyeremail\" )
ORDER BY RAND()
LIMIT 1";

$result = query($random_listing_query);
$result = $result->fetch_assoc();
$random_item = $result["auctionID"];

// Redirect
$randomURL = "listing.php?item_id='$random_item'";
header('Location: '.$randomURL);
?>