<?php
include_once("header.php");
include_once("utilities.php");

$random_listing_query = "SELECT auctionID FROM auctions
ORDER BY RAND()
LIMIT 1";

$result = query($random_listing_query);
$result = $result->fetch_assoc();
$random_item = $result["auctionID"];
$randomURL = "listing.php?item_id='$random_item'";


header('Location: '.$randomURL);
?>