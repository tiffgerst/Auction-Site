<?php session_start() ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" type="image/png" href="assets/icon.png" />
  <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@100&family=Lobster&display=swap" rel="stylesheet">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">

  <title> Silly Goose </title>

  <style>
    /* Modify the background color */
    .navbar-custom {
      background-color: #a6c6e3;
      padding-left: 20px;
    }
    /* Modify brand and text color */
    .navbar-custom .btn,
    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-text {
      color: white;
      font-family: "Lobster", cursive;
      font-size: 40px;
    }
    #mascot:hover {
    animation: shake 1s;
  }
  .navbar-brand:hover > #mascot{
    animation: shake 1s;
  }

  @keyframes shake {
    0% {
      transform: translateX(0%);
    }

    15% {
      transform:  rotate(-10deg);
    }

    30% {
      transform:  rotate(5deg);
    }

    45% {
      transform:  rotate(-10deg);
    }

    60% {
      transform:  rotate(3deg);
    }

    75% {
      transform:  rotate(-5deg);
    }

    100% {
      transform: translateX(0%);
    }
  }
    a {
      font-family: 'Fira Sans', sans-serif;
    }

  </style>
</head>

<body>
  <!-- Header with logo -->
  <nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="browse.php"><img id = 'mascot'src="assets/SillyGoose.png" height="70px"> Silly Goose</a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">

        <?php
        // Displays either "login" or "logout" on the right, depending on user's
        // current status (session).
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
          echo '<a style="color:#FFF" class="nav-link" href="logout.php"><h3 style="font-family:Lobster"><b>Logout</b></h3></a>';
        }
        else {
          echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login</button>';
        }
        ?>

      </li>
    </ul>
  </nav>
  <!-- Links -->
  <nav class="navbar navbar-expand-lg">
    <ul class="navbar-nav align-middle">
      <li class="nav-item mx-1" style="min-width:150px;width:100%;text-align:center">
        <a class="nav-link" style="font-size:20px;font-family:Lobster,cursive;color:white;background-color:#a6c6e3;border-radius:30px" href="browse.php">Browse</a>
      </li>
      <?php
      $width = "min-width:150px;width:100%;text-align:center";
      $style = "font-size:20px;font-family:Lobster,cursive;color:white;background-color:#a6c6e3;border-radius:30px";

      // Buyer tabs
      if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {
        echo ('
      <li class="nav-item mx-1" style="'.$width.'">
        <a class="nav-link" style="'.$style.'" href="mybids.php">My Bids</a>
      </li>
      <li class="nav-item mx-1" style="'.$width.'">
        <a class="nav-link" style="'.$style.'" href="recommendations.php">Recommended</a>
      </li>
      <li class="nav-item mx-1" style="'.$width.'">
        <a class="nav-link" style="'.$style.'" href="hotStuff.php">Hot Right Now</a>
      </li>
      <li class="nav-item mx-1" style="'.$width.'">
        <a class="nav-link" style="'.$style.'" href="feeling_lucky.php">Feeling Lucky?</a>
      </li>');
      }
      // Seller tabs
      if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {
        echo ('
      <li class="nav-item mx-1" style="'.$width.'">
          <a class="nav-link" style="'.$style.'" href="mylistings.php">My Listings</a>
        </li>
      <li class="nav-item mx-1" style="'.$width.'">
          <a class="nav-link" style="'.$style.'" href="create_auction.php">+ Create auction</a>
        </li>');
      }
      // Edit credentials - appears for all accounts
      if (isset($_SESSION['account_type'])) {
        echo('
      <li class="nav-item mx-1" style="'.$width.'">
        <a class="nav-link" style="'.$style.'" href="edit_my_info.php">Edit Credentials</a>
      </li>');
      }
      ?>
    </ul>
  </nav>

  <!-- Login modal -->
  <div style="font-family: aria;" class="modal fade" id="loginModal">
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
              <input type="text" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary form-control">Sign in</button>
          </form>
          <div class="text-center">or <a href="register.php">create an account</a></div>
        </div>

      </div>
    </div>
  </div> <!-- End modal -->