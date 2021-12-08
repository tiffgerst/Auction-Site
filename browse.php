<?php include_once("header.php")?>
<?php require("utilities.php");?>

<div class="container">
<h2 class="my-3">Browse listings</h2>
<div id="searchSpecs"> <!-- start search specs bar -->
<form method="get" action="browse.php">
  <div class="row">
    <!-- Keyword search -->  
    <div class="col-md-4 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          <input type="text" class="form-control border-left-0" name="keyword" id="keyword" placeholder="Search for anything">
        </div>
      </div>
    </div>
    <!-- Category search -->
    <div class="col-md-2 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" name="cat" id="cat">
          <option selected value="">All categories</option>
          <?php 
          $category_list = query("SELECT * FROM category");
          while($category = $category_list->fetch_assoc()){
            echo("<option value=".$category['categoryName'].">".$category['categoryName']."</option>");
          };
          ?>
        </select>
      </div>
    </div>
    <!-- Sort by -->
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <select class="form-control" name="order_by" id="order_by">
          <option selected value="endDate ASC">Soonest expiry</option>
          <option value="current_price ASC">Price (low to high)</option>
          <option value="current_price DESC">Price (high to low)</option>
        </select>
      </div>
    </div>
    <!-- Show expired -->
    <div class="col-md-2 pr-0">
      <div class="form-group" style="vertical-align:middle">
      <label class="mx-2" for="expired">Show expired:</label>
      <input type="checkbox" id="expired" name="show_expired" value="TRUE">
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->
</div>

<?php
  // Process the GET variables
  
  // Keyword
  if (!isset($_GET['keyword'])) {
    $keyword = "%"; // Browse everything
  }
  else {
    $keyword = escape_string($_GET['keyword']);
    $keyword = "%".$keyword."%";
  }

  // Category
  if (!isset($_GET['cat'])) {
    // null used to skip including category criteria in the query    
    $category = null; 
  }
  else {
    $category = escape_string($_GET['cat']);
  }

  // Order by
  if (!isset($_GET['order_by'])||$_GET['order_by']=="endDate ASC") {
    $ordering = "endDate ASC"; // Sort by expiry date by default
  }
  else {
    $ordering = escape_string($_GET['order_by']);
  }

  // Show expired
  if (!isset($_GET['show_expired'])) {
    $expired = FALSE;
  }
  else {
    $expired = TRUE;
  }

  // Page
  if ((!isset($_GET['page']))||(!is_numeric($_GET['page']))) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }
?>

<div class="container mt-5"> <!-- container for listings and pagination -->
<ul class="list-group"> <!-- start displaying listings -->
<?php
  // Perform the necessary query for displaying results
  
  // Build core query
  $query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.picture,
  COALESCE(b.current_price,a.startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids'
  FROM
  (SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b
  RIGHT JOIN (SELECT * FROM auctions a WHERE a.title LIKE '".$keyword."' OR a.description LIKE '".$keyword."') a
  ON a.auctionID = b.auctionID";

  // Add optional arguments
  if ($expired == FALSE) {
    // Only look at auctions that haven't expired
    $query .= " WHERE a.endDate > CURRENT_TIME()";
    if ($category) {
      // Filter category
      $query .= "AND a.categoryName LIKE '".$category."'";
    }
  }
  else{
    if ($category != NULL) {
      // Filter category
      $query = "SELECT a.auctionID, a.title, a.description, a.endDate, a.picture,
      COALESCE(b.current_price,a.startPrice) AS 'current_price', COALESCE(b.num_bids,0) AS 'num_bids'
      FROM
      (SELECT auctionID, MAX(bidValue) AS 'current_price', COUNT(auctionID) AS 'num_bids' FROM bids GROUP BY auctionID) b
      RIGHT JOIN (SELECT * FROM auctions a WHERE a.title LIKE '".$keyword."' OR a.description LIKE '".$keyword."') a
      ON a.auctionID = b.auctionID
      WHERE a.categoryName LIKE '".$category."' ";
    }
  }
 

  // Order results
  $query .= " ORDER BY ".$ordering;

  // Perform query
  $result = query($query);
  $num_results = mysqli_num_rows($result);
  if ($num_results == 0) {
    echo('No results found');
    exit;
  }
  
  // Set pagination variables
  $results_per_page = 10;
  $max_page = ceil($num_results / $results_per_page);

  // Consider the current page when deciding what results to display
  $last_result = ($curr_page * 10)-1;
  
  // The above rule will not work on if every page is not full
  // (num_results not a multiple of 10)
  if ($last_result > $num_results-1) {
    $last_result = $num_results-1;
  }

  $first_result =  ($curr_page * 10) - 10;
  
  // Iterate through first_result -> last_result
  // To display listings on our selected page
  $i = $first_result;
    
  while ($i<=$last_result) {
    $result->data_seek($i);
    $row = $result->fetch_assoc();
    $item_id = $row['auctionID'];
    $title = $row['title'];
    $description = $row['description'];
    $end_date = new DateTime($row['endDate']);
    $num_bids = $row['num_bids'];
    $current_price = $row['current_price'];
    $image = $row["picture"];
      
    print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date, $image);
    $i+=1;
  }
?>
</ul> <!-- end displaying listings -->

<nav aria-label="Search results pages" class="mt-5"> <!-- Pagination for results listings -->
<ul class="pagination justify-content-center">
<?php

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>
</ul>
</nav>
</div> <!-- end container for listings and pagination -->

<?php include_once("footer.php")?>