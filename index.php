<?php
  # Get database credentials
  require_once('db_credentials.php');

  # Create a database connection
  $connection = mysqli_connect($dbhost,$dbuser,$dbpass);

  # Check if there is an auction database - create one if not
  mysqli_query($connection,"CREATE DATABASE IF NOT EXISTS auction;");
  mysqli_query($connection,"USE auction;");
 
  # Check for a users table
  $users = "CREATE TABLE IF NOT EXISTS users (user TEXT NOT NULL, password TEXT NOT NULL)";
  mysqli_query($connection, $users);
  mysqli_query($connection, "TRUNCATE TABLE users"); # Empty it
  mysqli_query($connection, "INSERT INTO users (user, password) VALUES ('Jeff','Bezos')");
  
  header("Location: browse.php");
?>
