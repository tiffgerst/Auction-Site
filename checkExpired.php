<?php include_once('utilities.php');?>
<?php
emailExpired();
function emailExpired(){
$five_minutes_ago = x_minutes_ago(5);
$now_string = x_minutes_ago(0);


// Query
$query = "SELECT auctionID,sellerEmail,startPrice,reservePrice,title FROM auctions WHERE endDate<'$now_string'
AND endDate>='$five_minutes_ago'";
$result = query($query);

if (mysqli_num_rows($result) == 0){
  echo('No results found');
}

else {
while ($row = $result->fetch_assoc()){
  $title = $row['title'];
  $auctionID = $row['auctionID'];
  $startPrice = $row['startPrice'];
  $reservePrice = $row['reservePrice'];
  $sellerEmail = $row['sellerEmail'];
  
  // Determine the circumstances of the auction expiry by querying the bids table
  $query = "SELECT buyerEmail, bidValue as 'highest_bid'
  FROM bids
  WHERE auctionID = $auctionID AND
  bidValue = (SELECT MAX(bidValue) FROM bids WHERE auctionID = $auctionID)";
  $bids_result = query($query);

  if (mysqli_num_rows($bids_result) == 0) {
    echo("\n\n");
    // No bids
    
    // Send an email to just the seller
    $body = "No one bidded on your auction ($title) you goof! :(";
    send_email($sellerEmail,"Un-Successful Auction | ID:$auctionID",$body);
  }
  else {

    $bid_row = $bids_result->fetch_assoc();
    $highest_bid = $bid_row['highest_bid'];
    $buyerEmail = $bid_row['buyerEmail'];
    
    if ($highest_bid<$reservePrice) {
      echo("\n\n");
      // Bids placed but reserve price not met

      // Send email to seller
      $body = "Your reserve price was not met solly solly";
      send_email($sellerEmail,"Un-Successful Auction | ID:$auctionID",$body);
      
      // Send an email to the highest bidder
      $body = "Dear Customer, although you placed the highest bid (£$highest_bid),
      you did not meet the reserve price so were un-successful at winning this auction
      Kind Regards,
      Jeff Bezos";
      send_email($buyerEmail, "Un-Successful Highest Bid | ID:$auctionID",$body);
    }
    else {
      echo("\n\n");
      // Successful auction
      $query = "SELECT addressLine, city, country, postcode FROM users WHERE email = '$buyerEmail'";
      $result = query($query);
      $row = $result->fetch_assoc();
      $address = $row['addressLine'];
      $city = $row['city'];
      $county = $row['country'];
      $postcode = $row['postcode'];

      // Send email to seller
      $body = "Someone bought your item: $title :^) Please contact the winner of the auction via $buyerEmail.\nYou can ship the item to: \n$address,\n$city,\n$county,\n$postcode ";
      send_email($sellerEmail,"SUCCESSFUL Auction | ID:$auctionID",$body);

      // Send an email to the highest bidder
      $body = "You won you stud! The seller should contact you soon. Here is their email just in case: $sellerEmail";
      send_email($buyerEmail, "SUCCESSFUL Highest Bid | ID:$auctionID",$body);
    }
  }
}
}
}
function x_minutes_ago($minutes) {
  $_ = new DateTime();
  $_ = $_->sub(new DateInterval('P0DT0H'.$minutes.'M0S'))->format('Y-m-d H:i:00');
  return $_;
}
?>