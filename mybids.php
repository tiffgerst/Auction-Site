<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My bids</h2>

<div class="container mt-5">
<ul class="list-group">

<?php
  // Build query
  $email = $_SESSION['username'];
  $sql = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture
  from auctions as a WHERE a.auctionID IN (SELECT auctionID FROM `bids` WHERE  buyerEmail = '$email')";
  
  // # Perform query
  $result = query($sql);
  
  # Use results to change display of results
  $num_results = mysqli_num_rows($result);

  if ($num_results>0) {
    while ($row = $result->fetch_assoc()) {
      $item_id = $row['auctionID'];
      $title = $row['title'];
      $description = $row['description'];
      $startPrice = $row['startPrice'];
      $end_date = new DateTime($row['endDate']);
      $image = $row['picture']; # Convert from string to DT object
      $x = query("Select COALESCE(COUNT(auctionID),0) as count from bids where auctionID = $item_id");
      $y = $x->fetch_assoc();
      $num_bids = $y['count'];
      
  
      if ($num_bids == 0) {
        $current_price = $startPrice;
      }
      else {
        $h = query("select COALESCE(MAX(bidValue),$startPrice) as price from bids where auctionID=$item_id");
        $o = $h->fetch_assoc();
        $current_price = $o['price'];
      }
        
      print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date, $image);
    }
  }
  else if ($num_results==0){
    # Result is empty
    echo('No results found');
  }
?>


<?php include_once("footer.php")?>