<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>
<p class="text-muted"> Here's a selection of auctions you haven't bid on that have high bidding activity. </p> 

<?php
$email = $_SESSION['username'];
$sql = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture
from auctions as a WHERE a.auctionID IN 
(select auctionID from bids where buyerEmail IN 
(select buyerEmail from bids where buyerEmail!=\"$email\" AND auctionID IN 
(select auctionID from bids WHERE buyerEmail = \"$email\")) AND auctionID NOT IN
(select auctionID as mine from bids WHERE buyerEmail = \"$email\")
Group by auctionID ORDER BY COUNT(auctionID)DESC) LIMIT 5";

$rec = query($sql);
$num_results = mysqli_num_rows($rec);

if ($num_results == 0) {
  echo('Recommendations will appear once you have placed a bid.');
  exit;
}

while ($row = $rec->fetch_assoc()) {
  $item_id = $row['auctionID'];
  $title = $row['title'];
  $desc = $row['description'];
  $startPrice = $row['startPrice'];
  $end_time = new DateTime($row['endDate']); # Convert from string to DT object
  $image = $row['picture'];
  $x = query("Select COALESCE(COUNT(auctionID),0) as count from bids where auctionID = $item_id");
  $y = $x->fetch_assoc();
  $num_bids = $y['count'];
  
  if ($num_bids == 0) {
    $price = $startPrice;
  }
  else {
    $h = query("select COALESCE(MAX(bidValue),$startPrice) as price from bids where auctionID=$item_id");
    $o = $h->fetch_assoc();
    $price = $o['price'];
  }
  print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time, $image);
  }
?>
</div>