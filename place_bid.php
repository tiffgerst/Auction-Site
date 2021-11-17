<?php include_once("utilities.php")?>
<?php session_start()?>
<?php

$bidValue = $_POST['bid']; # All bid value verification done via .js
$buyerEmail = $_SESSION['username'];
$auctionID = $_GET['item_id'];

# Generate a bid ID by querying the database
$result = query("SELECT bidID FROM bids");
$numrows = mysqli_num_rows($result);
$bidID = $numrows;

# INSERT
$query = sprintf("INSERT INTO bids VALUES (%g,'%s',%g,%g)",
    $bidID,
    $buyerEmail,
    $auctionID,
    $bidValue);

query($query);

# Notify and redirect
echo('<div class="text-center">Bid placed successfully! You will be redirected shortly</div>');
header('refresh:5;url="http://auction/listing.php?item_id='.$auctionID.'"');

?>