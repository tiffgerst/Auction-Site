-- Create database
CREATE DATABASE IF NOT EXISTS auction;
USE auction;

-- Create users table
DROP TABLE IF EXISTS users;
CREATE TABLE users ( 
  email VARCHAR(50) PRIMARY KEY,
  password VARCHAR(60), -- Hashed + Salted in PHP - needs 60 chars
  accountType VARCHAR(6),
  country VARCHAR(30),
  city VARCHAR(30),
  postcode VARCHAR(30)
  addressLine VARCHAR(30),
);

-- Create auctions table
DROP TABLE IF EXISTS auctions;
CREATE TABLE auctions (
  auctionID INT AUTO_INCREMENT PRIMARY KEY,
  sellerEmail VARCHAR(50),
  title VARCHAR(50),
  description VARCHAR(200),
  categoryName VARCHAR(50),
  reservePrice FLOAT(10,2),
  endDate DATETIME,
  startPrice FLOAT(10,2),
  picture VARCHAR(30)
);

INSERT INTO auctions (sellerEmail,title,description,categoryName,
reservePrice,endDate,startPrice,picture) VALUES 
  ('jeff@hotmail.co.uk','Milk','A bottle of milk','Food',
  10.50,'2021-12-31 22:00:00',5.50, 'milk.jpg'),-- Demo 1
  ('jeff@hotmail.co.uk','Cheese','Swiss Cheese','Food',
  11.50,'2021-12-05 22:00:00',6.9, 'cheese.jpg'); -- Demo 2

-- Create bids table
DROP TABLE IF EXISTS bids;
CREATE TABLE bids (
  bidID INT AUTO_INCREMENT PRIMARY KEY,
  buyerEmail VARCHAR(50),
  auctionID INT,
  bidValue FLOAT(10,2),
  bidDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

INSERT INTO bids (buyerEmail,auctionID,bidValue,bidDate) VALUES 
  ('jeff@hotmail.co.uk',1,25, '2020-03-10 17:16:18'); -- Demo 1

-- Create watchlist table
DROP TABLE IF EXISTS watching;
CREATE TABLE watching (
  buyerEmail VARCHAR(50),
  auctionID INT,
  PRIMARY KEY (buyerEmail, auctionID)
);

-- Create category table
DROP TABLE IF EXISTS category;
CREATE TABLE category (
  categoryID INT AUTO_INCREMENT PRIMARY KEY,
  categoryName VARCHAR(50)
);

INSERT INTO category (categoryName) VALUES 
  ('Shoes'),
  ('Pants'),
  ('Tops'),
  ('Dresses'),
  ('Skirts'),
  ('Suits'),
  ('Accessories');