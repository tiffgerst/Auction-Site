<?php include_once("header.php")?>
<?php require_once("utilities.php")?>

<div class="container">

<h2 class="my-3">My listings</h2>

<?php
$email = $_SESSION["username"];

$query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture,
COALESCE(b.current_price,startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids'
FROM
-- Use the bids table to determine what the highest bid is (if it exists)
(SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b
-- If the auction isn't in the bids table (0 bids) then 'current_price' will be null
RIGHT JOIN (SELECT * FROM auctions WHERE sellerEmail = '$email') a
ON a.auctionID = b.auctionID ORDER BY a.endDate";

$result = query($query);
$num_result = mysqli_num_rows($result);

if ($num_result==0){
  # Result is empty
  echo('No results found');
  exit;
}

while ($row = $result->fetch_assoc()) {
  $item_id = $row['auctionID'];
  $title = $row['title'];
  $description = $row['description'];
  $end_date = new DateTime($row['endDate']); # Convert from string to DT object
  $num_bids = $row['num_bids'];
  $picture = $row['picture'];
  $current_price = $row['current_price'];
  
  print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date,$picture);
}  
?>

<?php include_once("footer.php")?>