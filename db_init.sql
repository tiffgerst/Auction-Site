-- Create database
CREATE DATABASE IF NOT EXISTS auction;
USE auction;

-- Create users table
DROP TABLE IF EXISTS users;
CREATE TABLE users ( 
  email VARCHAR(50) PRIMARY KEY,
  password VARCHAR(50),
  accountType VARCHAR(50)
);
INSERT INTO users VALUES ('jeff@hotmail.co.uk','vvs','buyer'); -- Demo 1

-- Create auctions table
DROP TABLE IF EXISTS auctions;
CREATE TABLE auctions (
  auctionID INT PRIMARY KEY,
  sellerEmail VARCHAR(50),
  title VARCHAR(50),
  description VARCHAR(200),
  categoryName VARCHAR(50),
  reservePrice FLOAT(10,2),
  endDate DATETIME,
  startPrice FLOAT(10,2),
  picture VARCHAR(30)
);

INSERT INTO auctions VALUES 
  (1,1,'Milk','A bottle of milk','Food',10.50,'2021-12-31 22:00:00',5.50, 'milk.jpg'),-- Demo 1
  (2,2,'Cheese','Swiss Cheese','Food',11.50,'2021-12-05 22:00:00',6.9, 'cheese.jpg'); -- Demo 2

-- Create bids table
DROP TABLE IF EXISTS bids;
CREATE TABLE bids (
  bidID INT PRIMARY KEY,
  buyerEmail VARCHAR(50),
  auctionID INT,
  bidValue FLOAT(10,2)
);

INSERT INTO bids VALUES 
  (0,'jeff@hotmail.co.uk',1,25); -- Demo 1

-- Create watchlist table
DROP TABLE IF EXISTS watching;
CREATE TABLE watching (
  buyerEmail VARCHAR(50),
  auctionID INT,
  PRIMARY KEY (buyerEmail, auctionID)
 
);

-- Create order table
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
  orderID INT PRIMARY KEY,
  auctionID INT,
  bidValue FLOAT(10,2),
  buyerEmail VARCHAR(50)
);

-- Create category table
DROP TABLE IF EXISTS category;
CREATE TABLE category (
  categoryID INT PRIMARY KEY,
  categoryName VARCHAR(50)
);