<?php
include_once("header.php");
include_once("utilities.php");
?>
<div class="container">

<h2 class="my-3">Hey There Hot Stuff!</h2>
<?php 
$email = $_SESSION["username"];

// Check if there are auctions that the user has not bidded on
$sql = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture
from auctions as a where auctionID IN 
(SELECT auctionID from bids WHERE auctionID NOT IN
(SELECT auctionID from bids Where buyerEmail=\"$email\") 
GROUP BY auctionID ORDER BY COUNT(auctionID) DESC)";
$hot = query($sql);
$num_results = mysqli_num_rows($hot);

if ($num_results==0){
  # Result is empty
  //$youtube = "https://www.youtube.com/watch?v=rog8ou-ZepE";
  echo('Nothings hot right now :( except for you, silly goose!');
  //echo ("<iframe width='420' height='315'src=$youtube></iframe>");
  exit();
}

// For each auction that the user has not bidded on
while ($row = $hot->fetch_assoc()) {
  // Extract attributes from auctions table
  $item_id = $row['auctionID'];
  $title = $row['title'];
  $desc = $row['description'];
  $startPrice = $row['startPrice'];
  $end_time = new DateTime($row['endDate']);
  $image = $row['picture'];
  
  // Get information from the bids table
  $_ = query("SELECT COALESCE(COUNT(auctionID),0) as num_bids,
  COALESCE(MAX(bidValue,$startPrice)) as current_price
  FROM bids WHERE auctionID = $item_id");
  $row = $_->fetch_assoc();
  $num_bids = $row['num_bids'];
  $current_price = $row['current_price'];
  
  print_listing_li($item_id, $title, $desc, $current_price, $num_bids, $end_time, $image);
}
}

?>