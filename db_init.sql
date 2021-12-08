-- Create database
CREATE DATABASE IF NOT EXISTS auction;
USE auction;

-- Create users table
DROP TABLE IF EXISTS users;
CREATE TABLE users ( 
  email VARCHAR(50) PRIMARY KEY,
  password VARCHAR(50),
  accountType VARCHAR(50),
  country VARCHAR(30),
  address VARCHAR(100)
);
INSERT INTO users VALUES 
('jeff@hotmail.co.uk','vvs','seller', 'Germany', '123 Richest Street, Richest City, RI3H M3'),
('buyer@buyer.com','buyer','buyer', 'Germany', '2nd Richest Street, Richest City, RI3H M3'),
('tiff@gerst.com','psw1','seller', 'UK', '32 Charming Ave, Angel, W9283'),
('silly@goose.com','sgoose1','buyer', 'UK', '12 Tottenham court, London, W92qa'),
('miron@kiss.com','JinsKitchen','seller', 'Russia', '34 Hasbulla Road, Shoreditch, D3AF B0I'),
('leo@pat.com','loulou','buyer', 'France', '24 Booboo Street, Tutu City, 12344'),
('ben@thread.com','buyer','buyer', 'Jamaica', '34 Richest Street, Richest City, RI3H M3'),
('ewan@smith.com','braindead','buyer', 'USA', '2804 Ellendale Place, Los Angeles, 90089'),
('maggie@simpson.com','potato','buyer', 'USA', '27 Evergreen Terrace, Springfield, 90007');
 -- Demo 1

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
  ('jeff@hotmail.co.uk','Goose Sweater','A cashmere sweater with a goose on it','Tops',
  150.00,'2022-03-20 22:00:00',200.00, 'goosesweat.jpg'),
  ('tiff@gerst.com','Duck Shoes','Donald Duck High Heels','Shoes',
  400.00,'2022-12-05 23:59:59',999.00, 'duckshoe.jpg'),
  ('miron@kiss.com','Yellow Shoes','Duck shoes','Shoes',
  75.50,'2022-04-15 12:00:00',80.0, 'gooseshoe.jpg'),
  ('tiff@gerst.com','Duck in Pants','Pair of pants that comes with a duck','Pants',
  21.20,'2021-12-31 22:00:00',30.0, 'duckpants.png'),
  ('jeff@hotmail.co.uk','Duck dress','A dress with print on duckies','Dress',
  15.50,'2021-12-31 22:00:00',5.50, 'duckdress.jpg'),
  ('jeff@hotmail.co.uk','Goose Pin','Pin of a threatening goose','Accessories',
  5.50,'2021-12-31 22:00:00',5.50, 'goosepin.jpg');

-- Create bids table
DROP TABLE IF EXISTS bids;
CREATE TABLE bids (
  bidID INT AUTO_INCREMENT PRIMARY KEY,
  buyerEmail VARCHAR(50),
  auctionID INT,
  bidValue FLOAT(10,2),
  bidDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP);

INSERT INTO bids (buyerEmail,auctionID,bidValue,bidDate) VALUES 
  ('buyer@buyer.com',1,170, '2020-03-10 17:16:18'),
  ('silly@goose.com',1,200, '2020-03-10 17:16:18'),
  ('leo@pat.com',1,230, '2020-03-10 17:16:18'),
  ('ben@thread.com',1,255, '2020-03-10 17:16:18'),
  ('ewan@smith.com',1,300, '2020-03-10 17:16:18'),
  ('maggie@simpson.com',1,340, '2020-03-10 17:16:18'),
  ('buyer@buyer.com',2,450, '2020-03-10 17:16:18'),
  ('buyer@buyer.com',3,90, '2020-03-10 17:16:18'),
  ('buyer@buyer.com',4,20, '2020-03-10 17:16:18'),
  ('silly@goose.com',2,500, '2020-03-10 17:16:18'),
  ('silly@goose.com',3,110, '2020-03-10 17:16:18'),
  ('silly@goose.com',4,25, '2020-03-10 17:16:18'),
  ('silly@goose.com',5,19, '2020-03-10 17:16:18'),
  ('silly@goose.com',6,10, '2020-03-10 17:16:18'),
  ('leo@pat.com',2,510, '2020-03-10 17:16:18'),
  ('leo@pat.com',1,389, '2020-03-10 17:16:18'),
  ('ben@thread.com',1,431, '2020-03-10 17:16:10'),
  ('maggie@simpson.com',1,490, '2020-03-10 17:16:18'),
  ('ewan@smith.com',4,30, '2020-03-10 17:16:18'),
  ('ewan@smith.com',5,25, '2020-03-10 17:16:18'),
  ('ewan@smith.com',6,12, '2020-03-10 17:16:18'),
  ('ewan@smith.com',2,560, '2020-03-10 17:16:18');
   -- Demo 1

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