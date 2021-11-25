<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

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
if ($rec){
  $num_results = mysqli_num_rows($rec);
}
else{
  $num_results = 0;
}

if ($num_results>0) {
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
    echo"People who bid on the same items you did also bid on:";
    print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time, $image);
  }
}
else if ($num_results==0){
  # Result is empty
  echo('Recommendations will appear once you have placed a bid.');
}


  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  
  // TODO: Perform a query to pull up auctions they might be interested in.
  
  // TODO: Loop through results and print them out as list items.
  
?>