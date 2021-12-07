<?php include_once("header.php")?>
<?php require_once("utilities.php")?>

<div class="container my-5">

<?php
// Make sure the user is logged in and is a seller
if ((!isset($_SESSION['username']))||($_SESSION['account_type'] != 'seller')) {
    exit;
}

function check($data) {
    if (!isset($data)) {
        exit;
    }
    else{
        return $data;
    }
  }

// Read REQUIRED POST variables, check if they are set
$title = check($_POST['auctionTitle']);
$details = check($_POST['auctionDetails']);
$category = check($_POST['auctionCategory']);
$startPrice = check($_POST['auctionStartPrice']);
$endDate = check($_POST['auctionEndDate']);

// Check startPrice is numeric
if (!is_numeric($startPrice)) {
    exit;
}

// Reserve price is not required
if (!isset($_POST['auctionReservePrice'])) {
    // If it isn't set, set it to 0.01
    $reservePrice = 0.01;
}
else {
    // If it is set, validate it
    $reservePrice = $_POST['auctionReservePrice'];
    if (!is_numeric($reservePrice)) {
        exit;
    }
}

// Validate the end date
$endDate = DateTime::createFromFormat('Y-m-d\TH:i',$endDate);
$date_errors = DateTime::getLastErrors();
if (($date_errors['warning_count'] + $date_errors['error_count'] > 0) || ($endDate < new DateTime())) {
    exit;
}
$endDate = $endDate->format('Y-m-d H:i:00'); // MySQL required format

// Validate image type
$filename = $_FILES["auction_image"]["name"];
$file_type = $_FILES["auction_image"]["type"];
$extensions=array( 'image/jpeg', 'image/png', 'image/gif');
if(!in_array($file_type,$extensions)){
    echo('Please upload an image! Only JPEG, PNG and GIF are accepted.');
    header("refresh:5;url=create_auction.php");
    exit;
}

// Escape remaining non-date strings
$title = escape_string($title);
$details = escape_string($details);
$category = escape_string($category);
$newfilename = escape_string($filename);

// Make sure category is valid
$result = query("SELECT * FROM category WHERE categoryName = '$category'");
if (mysqli_num_rows($result)==0) {
    exit;
}

// Save the image with the (now validated) name
$filetempname = $_FILES["auction_image"]["tmp_name"];
move_uploaded_file($filetempname, "images/".$filename);

// INSERT
$query = "INSERT INTO auctions
(sellerEmail,title,description,categoryName,
reservePrice,endDate,startPrice,picture) VALUES
('$username','$title','$details','$category',
$reservePrice,'$endDate',$startPrice,'$filename')";

query($query);

// Success message, get auction ID to give a redirect
$id = query("SELECT MAX(auctionID) as 'id' FROM auctions")->fetch_assoc()['id'];
echo('<div class="text-center">Auction successfully created! <a href="listing.php?item_id='.$id.'">View your new listing.</a></div>');
?>

</div>

<?php include_once("footer.php")?>