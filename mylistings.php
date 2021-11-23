<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
$email = $_SESSION["username"];
$query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, ";
  $query .= "COALESCE(b.current_price,startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids' ";
  $query .= "FROM ";
  $query .= "(SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b ";
  $query .= "RIGHT JOIN (SELECT * FROM auctions a WHERE a.sellerEmail = '$email' ) a ";
  $query .= "ON a.auctionID = b.auctionID ORDER BY a.endDate";

  $result = query($query);
  if($result){
    $num_result = mysqli_num_rows($result);
  }
  else{
    $num_result = 0;
  }
  if ($num_result>0) {
    while ($row = $result->fetch_assoc()) {
      $item_id = $row['auctionID'];
      $title = $row['title'];
      $description = $row['description'];
      $end_date = new DateTime($row['endDate']); # Convert from string to DT object
      $num_bids = $row['num_bids'];
      
      if ($num_bids == 0) {
        $current_price = $row['startPrice'];
      }
      else {
        $current_price = $row['current_price'];
      }
        
      print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
    }
  }
  else if ($num_result==0){
    # Result is empty
    echo('No results found');
  }
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.
  
  
  // TODO: Check user's credentials (cookie/session).
  
  // TODO: Perform a query to pull up their auctions.
  
  // TODO: Loop through results and print them out as list items.
  
?>

<?php include_once("footer.php")?>