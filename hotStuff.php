<?php
include_once("header.php");
include_once("utilities.php");
?>
<div class="container">

<h2 class="my-3">Hey There Hot Stuff!</h2>
<?php 
$email = $_SESSION["username"];
$sql = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture
        from auctions as a where auctionID IN 
        (SELECT auctionID from bids WHERE auctionID NOT IN
        (SELECT auctionID from bids Where buyerEmail=\"$email\") 
        GROUP BY auctionID ORDER BY COUNT(auctionID) DESC)";
$hot = query($sql);
$num_results = mysqli_num_rows($hot);

if ($num_results>0) {
    while ($row = $hot->fetch_assoc()) {
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
  }
  else if ($num_results==0){
    # Result is empty
    //$youtube = "https://www.youtube.com/watch?v=rog8ou-ZepE";
    echo('Nothings hot right now :( except for you, silly goose!');
    //echo ("<iframe width='420' height='315'src=$youtube></iframe>");
  }
  
?>
