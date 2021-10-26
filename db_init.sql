-- Create database
CREATE DATABASE IF NOT EXISTS auction;
USE auction;

-- Create users table
DROP TABLE IF EXISTS users;
CREATE TABLE users (email VARCHAR(50), password VARCHAR(50), accountType VARCHAR(50));
INSERT INTO users VALUES ( -- Demo values
  'jeff@hotmail.co.uk',
  'vvs',
  'buyer');

-- Create auctions table
DROP TABLE IF EXISTS auctions;
CREATE TABLE auctions (
  auctionID INT,
  sellerID INT,
  title VARCHAR(50),
  description VARCHAR(200),
  category VARCHAR(25),
  -- picture BLOB,
  reservePrice FLOAT(10,2),
  endDate DATETIME,
  startPrice FLOAT(10,2));

INSERT INTO auctions VALUES 
  (1,1,'Milk','A bottle of milk','Food',10.50,'2021-12-31 22:00:00',5.50), -- Demo 1
  (2,2,'Cheese','Swiss Cheese','Food',11.50,'2021-12-05 22:00:00',6.9); -- Demo 2