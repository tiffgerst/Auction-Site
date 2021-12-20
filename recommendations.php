<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<?php
$email = $_SESSION['username'];

if ($_SESSION['account_type']=='seller') {
  exit;
}

// Can't make recommendations without bids
$num_auctions = mysqli_num_rows(query("SELECT auctionID FROM bids WHERE buyerEmail = '$email' LIMIT 1"));

if ($num_auctions == 0) {
  echo("<p class='text-muted'>Place bids to get recommendations!</p>");
  exit;
}

/* 
Recommendations based on "users also bid on"
*/

// Build query
$query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture
from auctions as a 
WHERE a.auctionID IN (
SELECT auctionID from bids
WHERE buyerEmail IN 
-- Look for buyers who aren't the current buyer
(select buyerEmail from bids where buyerEmail!=\"$email\" AND auctionID IN
-- Who have bidded on the same auctions as the current buyer
(select auctionID from bids WHERE buyerEmail = \"$email\")) 
AND auctionID NOT IN
-- Filter out auctions the buyer has already bid on
(select auctionID as mine from bids WHERE buyerEmail = \"$email\")
)
AND endDate > CURRENT_TIME()
LIMIT 5";

$result = query($query);
$num_results = mysqli_num_rows($result);

if ($num_results != 0) {
  echo("<p>Users also bid on (based on your bidding activity)</p>");

  while ($row = $result->fetch_assoc()) {
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

/* 
Recommendations based on categories
*/

$query = "SELECT categoryName,COUNT(categoryName) as 'n_auctions'
FROM auctions
WHERE auctionID IN (SELECT auctionID FROM bids WHERE buyerEmail = '$email')
GROUP BY categoryName
ORDER BY COUNT(categoryName) DESC";

$result = query($query);
$breakdown = "<p class='mt-4'>Your category break down :) / ";

$highest = $result->fetch_assoc();
$highest_category = $highest['categoryName'];
$highest_bids = $highest['n_auctions'];
$breakdown .= $highest_category.": <b>".$highest_bids."</b> auctions / ";

while ($row = $result->fetch_assoc()) {
  $row_category = $row['categoryName'];
  $row_bids = $row['n_auctions'];
  $breakdown .= $row_category.": <b>{$row_bids}</b> auctions / ";
}

$breakdown .= "</p>";
echo($breakdown);



$query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.startPrice, a.picture, a.reservePrice,
COALESCE(b.current_price,startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids'
FROM
-- Use the bids table to determine what the highest bid is (if it exists)
(SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b
-- If the auction isn't in the bids table (0 bids) then 'current_price' will be null
RIGHT JOIN 
(SELECT * FROM auctions WHERE categoryName = '$highest_category'
AND endDate > CURRENT_TIME() AND auctionID NOT IN (SELECT auctionID FROM bids WHERE buyerEmail = '$email')) a
ON a.auctionID = b.auctionID";

$result = query($query);

if (mysqli_num_rows($result)==0) {
  echo("<p>You are bidding on all live $highest_category auctions!</p>");
  exit;
}

echo("<p>Here are some more listings from the $highest_category category</p>");
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
</div>