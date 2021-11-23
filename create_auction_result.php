<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container my-5">

<?php
    $filename = $_FILES["auction_image"]["name"];
    $filetempname = $_FILES["auction_image"]["tmp_name"];
    $folder = 'images/';
    move_uploaded_file($filetempname, $folder.$filename);

# Reserve price is not required -> if it's not set, set it to 0.01
# I.e. auctions that have no bids don't go through
if (empty($_POST['auctionReservePrice'])) {
    $reservePrice = 0.01;
}
else {
    $reservePrice = $_POST['auctionReservePrice'];
}

# Generate an ID for the auction by finding the maximum ID
$id = (query("SELECT MAX(auctionID) FROM auctions")->fetch_assoc()['MAX(auctionID)'])+1;

# Convert end date to the correct format
$endDate = date('Y-m-d H:i:00',strtotime($_POST['auctionEndDate']));

$query = sprintf("INSERT INTO auctions VALUES (%g,'%s','%s','%s','%s',%g,'%s',%g, '%s')",
    $id,
    $_SESSION['username'],
    $_POST['auctionTitle'],
    $_POST["auctionDetails"],
    $_POST['auctionCategory'],
    $reservePrice,
    $endDate,
    $_POST['auctionStartPrice'],
    $filename);

query($query);

// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="listing.php?item_id='.$id.'">View your new listing.</a></div>');

?>

</div>

<?php include_once("footer.php")?>