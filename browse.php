<?php include_once("header.php")?>
<?php require("utilities.php")?>

<div class="container">

<h2 class="my-3">Browse listings</h2>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-5 pr-0">
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
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" name="cat" id="cat">
          <option selected value="">All categories</option>
          <option value="fill">Fill me in</option>
          <option value="with">with options</option>
          <option value="populated">populated from a database?</option>
        </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <select class="form-control" name="order_by" id="order_by">
          <option selected value="endDate ASC">Soonest expiry</option>
          <option value="pricelow">Price (low to high)</option>
          <option value="pricehigh">Price (high to low)</option>
        </select>
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
  # Because no form has been submitted by default
  # _GET will be empty in the first instance
  # We need to set defaults

  if (!isset($_GET['keyword'])) {
    $keyword = "%"; # Browse everything
  }
  else {
    $keyword = "%".$_GET['keyword']."%";
  }

  if (!isset($_GET['cat'])) {
    # This null will be used to avoid
    # Including category criteria in the query    
    $category = null; 
  }
  else {
    $category = $_GET['cat'];
  }
  
  if (!isset($_GET['cat'])) {
    $ordering = "endDate ASC"; # Sort by expiry date by default
  }
  else {
    $ordering = $_GET['order_by'];
  }

  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }
?>

<div class="container mt-5">
<ul class="list-group">

<?php
  # Build query
  $query = "SELECT auctionID, title, description, endDate, startPrice FROM auctions WHERE title OR description LIKE '".$keyword."'";
  if ($category) {
    $query .= " AND category = '".$category."'";
  }
  $query .= " ORDER BY ".$ordering;

  # Perform query
  $result = query($query);
  
  # Use results to change display of results
  $num_results = mysqli_num_rows($result);
  $results_per_page = 10;
  $max_page = ceil($num_results / $results_per_page);

  if ($num_results>0) {
    while ($row = $result->fetch_assoc()) {
      $item_id = $row['auctionID'];
      $title = $row['title'];
      $description = $row['description'];
      $end_date = new DateTime($row['endDate']); # Convert from string to DT object
      $check_bids = check_bids($item_id,$row['startPrice']);
      $num_bids = $check_bids[0]; $current_price = $check_bids[1];
        
      print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
    }
  }
  else if ($num_results==0){
    # Result is empty
    echo('No results found');
  }
?>

</ul>

<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
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


</div>



<?php include_once("footer.php")?>