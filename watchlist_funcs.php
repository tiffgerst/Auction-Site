 <?php
 require_once("utilities.php");
 session_start();

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

// Extract arguments from the POST variables:
$item_id = $_POST['arguments'][0];

if (!is_numeric($item_id)){
  return;
}

$email = $_SESSION["username"];

if ($_POST['functionname'] == "add_to_watchlist") {
  // TODO: Update database and return success/failure.
  query("INSERT INTO watching (buyerEmail, auctionID) VALUES ('$email', $item_id)");
  $res = "success";
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
  // TODO: Update database and return success/failure.
  query("DELETE FROM watching WHERE buyerEmail = '$email' AND auctionID=$item_id");
  $res = "success";
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo $res;

?>
