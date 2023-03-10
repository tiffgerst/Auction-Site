<?php
include_once("header.php");
include_once("utilities.php");
?>
<div class="container">

<h2 class="my-3">Hey There Hot Stuff!</h2>
<p class="text-muted"> Live auctions will appear here if you haven't bid on them and they have relatively high amounts of bids. </p> 
<?php 
$email = $_SESSION["username"];

// Check if there are auctions (with bids) that the user has not bidded on
$sql = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture, n.popularity
FROM 
-- Table with all auctions that currently have bids, which the buyer has not bidded on, and haven't expired (a)
(SELECT * FROM auctions WHERE auctionID IN (SELECT auctionID FROM bids WHERE buyerEmail <> '$email') AND auctionID NOT IN
(SELECT auctionID from bids WHERE buyerEmail = \"$email\") AND endDate > CURRENT_TIME()) a
LEFT JOIN 
-- Table with the number of bids for each auction (n)
(SELECT auctionID, COUNT(auctionID) AS 'popularity' FROM bids GROUP BY auctionID) n
ON a.auctionID = n.auctionID
-- Take the top 3 most popular
ORDER BY popularity DESC LIMIT 3";
$hot = query($sql);

$num_results = mysqli_num_rows($hot);

if ($num_results==0){
  echo("Nothing's hot right now :( except for you, silly goose!");
  exit;
}

// For each auction in the result
while ($row = $hot->fetch_assoc()) {
  // Extract attributes
  $item_id = $row['auctionID'];
  $title = $row['title'];
  $desc = $row['description'];
  $startPrice = $row['startPrice'];
  $end_time = new DateTime($row['endDate']);
  $image = $row['picture'];
  
  // Get current price and number of bids from bids table
  $_ = query("SELECT COALESCE(COUNT(auctionID),0) as 'num_bids',
  COALESCE(MAX(bidValue),$startPrice) as 'current_price'
  FROM bids WHERE auctionID = $item_id GROUP BY auctionID");
  $row = $_->fetch_assoc();
  $num_bids = $row['num_bids'];
  $current_price = $row['current_price'];
  
  print_listing_li($item_id, $title, $desc, $current_price, $num_bids, $end_time, $image);
  }
?>