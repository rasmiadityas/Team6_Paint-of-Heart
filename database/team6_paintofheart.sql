-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2021 at 10:03 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `team6_paintofheart`
--
CREATE DATABASE IF NOT EXISTS `team6_paintofheart` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `team6_paintofheart`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `catName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `catName`) VALUES
(8, 'Aluminum'),
(9, 'Brick'),
(4, 'Concrete'),
(5, 'Granite'),
(1, 'Plastic'),
(10, 'Plexiglass'),
(6, 'Rubber'),
(3, 'Steel'),
(2, 'Stone'),
(7, 'Wood');

-- --------------------------------------------------------

--
-- Table structure for table `order_register`
--

CREATE TABLE `order_register` (
  `id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `tot_price` decimal(20,2) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_register`
--

INSERT INTO `order_register` (`id`, `fk_user_id`, `tot_price`, `date`) VALUES
(1, 3, '186.00', '2020-04-14 12:45:00'),
(2, 4, '236.50', '2020-05-14 13:56:00'),
(3, 5, '346.00', '2020-06-14 21:57:00'),
(4, 6, '141.00', '2020-07-14 15:05:00'),
(5, 7, '338.50', '2020-08-14 10:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `fk_category` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `description` text NOT NULL,
  `image` text NOT NULL,
  `discount` int(3) NOT NULL DEFAULT 0,
  `visibility` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `fk_category`, `price`, `description`, `image`, `discount`, `visibility`) VALUES
(1, 'Aqua', 1, '31.50', 'Create your blue dreamscape', '../pictures/heart1aqua.png', 0, 1),
(2, 'Lavender', 2, '33.00', 'Soft shell', '../pictures/heart2lavender.png', 0, 1),
(3, 'Light Pink', 4, '36.00', 'Give concrete a soft shell', '../pictures/heart3lightpink.png', 0, 1),
(4, 'Crimson', 1, '30.00', 'Crimson is all you need', '../pictures/heart4crimson.png', 0, 1),
(5, 'Plum', 5, '37.50', 'A perfect match', '../pictures/heart5plum.png', 20, 1),
(6, 'Peach Puff', 1, '32.00', 'Sometime a little color goes a long way', '../pictures/heart6peachpuff.png', 0, 1),
(7, 'Coral', 2, '33.50', 'Intense yet subtle', '../pictures/heart7coral.png', 0, 1),
(8, 'Yellow', 3, '34.50', 'This will brighten your surroundings', '../pictures/heart8yellow.png', 0, 1),
(9, 'Teal', 10, '45.00', 'Teal is always in style', '../pictures/heart9teal.png', 0, 1),
(10, 'Midnight Blue', 2, '34.00', 'A rich and powerful color', '../pictures/heart10midnightblue.png', 50, 1),
(11, 'Red', 3, '35.00', 'Go red or go home', '../pictures/heart11red.png', 0, 1),
(12, 'Goldenrod', 8, '42.00', 'A color suitable for royalty', '../pictures/heart12gold.png', 0, 1),
(13, 'Gray', 9, '43.50', 'A little goes a long way', '../pictures/heart13gray.png', 0, 1),
(14, 'Plum', 2, '34.00', 'For luxurious surrounding', '../pictures/heart5plum.png', 0, 1),
(15, 'Green', 7, '40.50', 'Like nature intended it', '../pictures/heart14green.png', 0, 1),
(16, 'Light Blue', 5, '38.00', 'Feels like a bright sunny day', '../pictures/heart15lightblue.png', 0, 1),
(17, 'Magenta', 8, '42.50', 'Dare to be bold', '../pictures/heart20magenta.png', 70, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `fk_item_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `fk_user_id` int(11) NOT NULL,
  `fk_item_id` int(11) NOT NULL,
  `review` text NOT NULL,
  `score` int(2) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `fk_user_id`, `fk_item_id`, `review`, `score`, `date`) VALUES
(1, 3, 5, 'Its runny like milk and has by far the worst smell I have ever smelt in a paint. I have used many paints and have years of painting experience. It says eco friendly after all so I thought it wouldn\'t have much of a smell but it smells like mothballs and metallic and lasts for many days.', 6, '2020-10-14 12:45:00'),
(2, 4, 12, 'I was pleasantly surprised with this paint! It turned out beautiful. Exactly the color I was looking for. Safe for my pets', 9, '2020-11-14 13:56:00'),
(3, 4, 17, 'First off, the paint itself is perfect! It did a beautiful job in my home. Second, the price... what a great value!', 9, '2020-11-14 14:16:00'),
(4, 5, 9, 'The color is amazing, very true to what is advertised, it does look lighter and shiny until it dries all the way then its dark and almost matte. The payoff is great, I only did two coats, first one covered pretty well (it was over cream and black paint) so I only did a second to even any streaks and spots I missed.', 10, '2020-12-14 21:57:00'),
(5, 6, 10, 'Paint is a little watery at first but works really well after stirring. Just be sure to stir really really well for at least a couple of minutes. You’ll definitely have to do two coats for even coverage unless you prime first.', 10, '2020-12-14 22:57:00'),
(6, 7, 10, 'Even though the final color did come out very pretty and worked for my room but it’s not the color I was made to believe I was buying and sorry that’s kinda the point of buying something, getting exactly what you paid for.', 6, '2021-01-14 15:05:00'),
(7, 7, 13, 'As far as colors go they have a nice selection, the paint is a little thin so you\'ll need 2-3 coats. The smell is not so bad but definitely bothered me.', 7, '2021-01-14 15:15:00'),
(9, 3, 3, 'It did smell a little bit much than what I\'m used to but it was easy to apply and loved the results I really hate painting but this paint has made it less annoying. It\'s cheap and the best part I don\'t have to stand in line to get paint.', 8, '2021-02-14 10:16:00'),
(10, 5, 3, 'Color is pretty. However, it goes on really sheer and will take several coats to thicken. It also smells really strong.', 8, '2021-03-14 09:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `cartId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productName` varchar(100) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `orderId` int(11) NOT NULL,
  `fk_userId` int(11) NOT NULL,
  `fk_productId` int(11) NOT NULL,
  `productName` varchar(250) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(5) NOT NULL DEFAULT 0,
  `regID` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`orderId`, `fk_userId`, `fk_productId`, `productName`, `quantity`, `price`, `image`, `date`, `status`, `regID`) VALUES
(1, 3, 5, 'Plum', 5, '150.00', '../pictures/heart5plum.png', '2020-04-14 12:45:00', 1, 1),
(2, 3, 3, 'Light Pink', 1, '36.00', '../pictures/heart3lightpink.png', '2020-04-14 12:45:00', 1, 1),
(3, 4, 12, 'Goldenrod', 3, '126.00', '../pictures/heart12gold.png', '2020-05-14 13:56:00', 1, 2),
(4, 4, 17, 'Magenta', 2, '25.50', '../pictures/heart20magenta.png', '2020-05-14 13:56:00', 1, 2),
(5, 4, 10, 'Midnight Blue', 5, '85.00', '../pictures/heart10midnightblue.png', '2020-05-14 13:56:00', 1, 2),
(6, 5, 9, 'Teal', 3, '135.00', '../pictures/heart9teal.png', '2020-06-14 21:57:00', 1, 3),
(7, 5, 3, 'Light Pink', 4, '144.00', '../pictures/heart3lightpink.png', '2020-06-14 21:57:00', 1, 3),
(8, 5, 7, 'Coral', 2, '67.00', '../pictures/heart7coral.png', '2020-06-14 21:57:00', 1, 3),
(9, 6, 10, 'Midnight Blue', 3, '51.00', '../pictures/heart10midnightblue.png', '2020-07-14 15:05:00', 1, 4),
(10, 6, 9, 'Teal', 2, '90.00', '../pictures/heart9teal.png', '2020-07-14 15:05:00', 1, 4),
(11, 7, 10, 'Midnight Blue', 5, '85.00', '../pictures/heart10midnightblue.png', '2020-08-14 10:06:00', 1, 5),
(12, 7, 13, 'Gray', 1, '43.50', '../pictures/heart13gray.png', '2020-08-14 10:06:00', 1, 5),
(13, 7, 12, 'Goldenrod', 5, '210.00', '../pictures/heart12gold.png', '2020-08-14 10:06:00', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_wish`
--

CREATE TABLE `tbl_wish` (
  `wishId` int(11) NOT NULL,
  `fk_userId` int(11) NOT NULL,
  `fk_productId` int(11) NOT NULL,
  `productName` varchar(250) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_state` int(1) NOT NULL DEFAULT 1,
  `user_level` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `f_name`, `l_name`, `address`, `email`, `password`, `user_state`, `user_level`) VALUES
(1, 'User', 'User', '35 Hanson Park', 'user0@user.com', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 1, 0),
(2, 'Admin', 'Admin', '1 Hermina Place', 'admin@admin.com', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', 1, 1),
(3, 'Anna', 'Alex', '1 Astrasse', 'usera@mail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, 0),
(4, 'Bella', 'Brian', '2 Bstrasse', 'userb@mail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, 0),
(5, 'Carol', 'Charlie', '3 Cstrasse', 'userc@mail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, 0),
(6, 'Daisy', 'Dean', '4 Dstrasse', 'userd@mail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, 0),
(7, 'Emma', 'Elias', '5 Estrasse', 'usere@mail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`catName`);

--
-- Indexes for table `order_register`
--
ALTER TABLE `order_register`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`fk_category`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`fk_user_id`),
  ADD KEY `fk_item_id` (`fk_item_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`fk_user_id`),
  ADD KEY `fk_item_id` (`fk_item_id`);

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `fk_productId` (`fk_productId`),
  ADD KEY `fk_userId` (`fk_userId`),
  ADD KEY `regID` (`regID`);

--
-- Indexes for table `tbl_wish`
--
ALTER TABLE `tbl_wish`
  ADD PRIMARY KEY (`wishId`),
  ADD KEY `fk_productId` (`fk_productId`),
  ADD KEY `fk_userId` (`fk_userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_wish`
--
ALTER TABLE `tbl_wish`
  MODIFY `wishId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_register`
--
ALTER TABLE `order_register`
  ADD CONSTRAINT `order_register_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`fk_category`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `question_ibfk_2` FOREIGN KEY (`fk_item_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`fk_item_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD CONSTRAINT `tbl_cart_ibfk_1` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD CONSTRAINT `tbl_order_ibfk_1` FOREIGN KEY (`fk_productId`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_order_ibfk_2` FOREIGN KEY (`fk_userId`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_order_ibfk_3` FOREIGN KEY (`regID`) REFERENCES `order_register` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `tbl_wish`
--
ALTER TABLE `tbl_wish`
  ADD CONSTRAINT `tbl_wish_ibfk_1` FOREIGN KEY (`fk_productId`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_wish_ibfk_2` FOREIGN KEY (`fk_userId`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
