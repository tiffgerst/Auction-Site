<?php
  /*
  Define database
  */
  
  require_once('db_credentials.php'); # Get database credentials
  $connection = mysqli_connect($dbhost,$dbuser,$dbpass); # Create a database connection

  # Check if there is an auction database - create one if not
  mysqli_query($connection,"CREATE DATABASE IF NOT EXISTS auction;");
  mysqli_query($connection,"USE auction;");
  
  /*
  Define tables
  */

  # Users table
  $users = "CREATE TABLE IF NOT EXISTS users (user TEXT NOT NULL, password TEXT NOT NULL)";
  mysqli_query($connection, $users);
  mysqli_query($connection, "TRUNCATE TABLE users"); # Empty it
  mysqli_query($connection, "INSERT INTO users (user, password) VALUES ('Jeff','Bezos')"); # Demo val

  # Auction table
  mysqli_query($connection, "DROP TABLE IF EXISTS auctions;");
  $auction = "CREATE TABLE IF NOT EXISTS auctions (";
  $auction .= "auctionID INT, ";
  $auction .= "sellerID INT, ";
  $auction .= "title VARCHAR(50), ";
  $auction .= "description VARCHAR(200), ";
  $auction .= "category VARCHAR(25), ";
  #$auction .= "picture BLOB, ";
  $auction .= "reservePrice FLOAT(10,2), ";
  $auction .= "endDate DATETIME, ";
  $auction .= "startPrice FLOAT(10,2))";
  mysqli_query($connection, $auction);
  
  # Auction table demo
  $first_auction = "INSERT INTO auctions VALUES ";
  $first_auction .= "(1,1,'Milk','A bottle of milk','Food',10.50";
  $first_auction .= ",'2021-12-31 22:00:00'";
  $first_auction .= ",5.50)";
  mysqli_query($connection,$first_auction);
  $first_auction = "INSERT INTO auctions VALUES ";
  $first_auction .= "(2,2,'Cheese','Swiss Cheese','Food',11.50";
  $first_auction .= ",'2021-12-05 22:00:00'";
  $first_auction .= ",6.9)";
  mysqli_query($connection,$first_auction);
  header("Location: browse.php");
?>