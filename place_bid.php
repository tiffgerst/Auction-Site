<?php include_once("utilities.php")?>
<?php session_start()?>
<?php
// If user is not signed in, redirect to log-in
if (!isset($_SESSION['username'])) {
    echo('Please log-in to place bids');
    echo('<div style="font-family: arial" class="modal fade" id="loginModal">
    <div class="modal-dialog">
      <div class="modal-content">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Login</h4>
        </div>
  
        <!-- Modal body -->
        <div class="modal-body">
          <form method="post" action="login_result.php">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="text" class="form-control" name="email" id="email" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary form-control">Sign in</button>
          </form>
          <div class="text-center">or <a href="register.php">create an account</a></div>
        </div>
  
      </div>
    </div>
  </div>');
  exit();
}

// HTML validates post
$bidValue = $_POST['bid'];
$buyerEmail = $_SESSION['username'];
$auctionID = $_GET['item_id'];

// Check for to see if the user placing a new bid
// Is already the highest bidder
$query = "SELECT buyerEmail, MAX(bidValue) AS 'maxBid'
FROM bids
WHERE auctionID = ".$auctionID."
GROUP BY buyerEmail
ORDER BY maxBid DESC";
$initialResult = query($query);

if (mysqli_num_rows($initialResult)>0){
    $initialResult =  $initialResult -> fetch_assoc();
    $highestBidderEmail = $initialResult["buyerEmail"];
    
    if ($highestBidderEmail == $buyerEmail){
        echo('<div class="text-center">You are already the highest bidder! Your bid has not been registered and you will be redirected shorty.</div>');
        header('refresh:5;url="listing.php?item_id='.$auctionID.'"');
        exit();
    }
}

// Generate a bid ID by querying the database
$result = query("SELECT bidID FROM bids");
$numrows = mysqli_num_rows($result);
$bidID = $numrows;
$bidDate = date('Y-m-d H:i:s');

// INSERT
$query = "INSERT INTO bids (bidID, buyerEmail, auctionID, bidValue, bidDate) VALUES ('$bidID','$buyerEmail','$auctionID','$bidValue','$bidDate')";

query($query);

# Notify and redirect

echo('<div class="text-center">Bid placed successfully! You will be redirected shortly</div>');
header('refresh:5;url="listing.php?item_id='.$auctionID.'"');
?>