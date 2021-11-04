<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container my-5">

<?php

# All valid
if (!isset(
$_POST['auctionTitle'],
$_POST["auctionDetails"],
$_POST['auctionCategory'],
$_POST['auctionReservePrice'],
$_POST['auctionEndDate'],
$_POST['auctionStartPrice'])) {
    echo('All fields must be completed - try again');
    exit;
}

# Generate an ID for the auction by finding the maximum ID
$id = (query("SELECT MAX(auctionID) FROM auctions")->fetch_assoc()['MAX(auctionID)'])+1;

# Convert end date to the correct format
$endDate = date('Y-m-d H:i:00',strtotime($_POST['auctionEndDate']));

$query = sprintf("INSERT INTO auctions VALUES (%g,'%s','%s','%s','%s',%g,'%s',%g)",
    $id,
    $_SESSION['username'],
    $_POST['auctionTitle'],
    $_POST["auctionDetails"],
    $_POST['auctionCategory'],
    $_POST['auctionReservePrice'],
    $endDate,
    $_POST['auctionStartPrice']);

query($query);

// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');

?>

</div>


<?php include_once("footer.php")?>