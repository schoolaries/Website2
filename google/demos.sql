-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2017 at 08:44 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demos`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `uid` int(5) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Subject` varchar(50) NOT NULL,
  `Message` varchar(100) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`uid`, `Name`, `Email`, `Subject`, `Message`, `datetime`) VALUES
(13, 'mingkang', 'mk@hotmail.com', 'fk this site', 'once i get my cert', '2017-02-12 08:52:33'),
(14, 'mingkang', 'mk@hotmail.com', 'fk this site', 'once i get my cert', '2017-02-12 08:58:21'),
(15, 'ian', 'peh@hotmail.com', '123', 'this is swap', '2017-02-12 12:42:14'),
(16, 'hello', 'its', 'me', 'i been wondering', '2017-02-12 13:03:50'),
(17, 'iasdia', 'disao@hotmail.com', 'idja', 'ijdisa', '2017-02-12 17:35:59'),
(18, 'iasdia', 'disao@hotmail.com', 'idja', 'ijdisa', '2017-02-12 17:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`email`) VALUES
('gg@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_code` varchar(60) NOT NULL,
  `product_name` varchar(60) NOT NULL,
  `product_desc` tinytext NOT NULL,
  `product_img_name` varchar(60) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `product_name`, `product_desc`, `product_img_name`, `price`) VALUES
(1, 'PD01', 'Tower Vodka ', 'Texas - Crafted in Pilot Point Texas, Tower Vodka proves that a top-shelf vodka doesn\'t always have to have a top-shelf price. Gluten-free, made from corn and distilled six times for a clean, crisp mouth feel with a silky smooth finish. Double Gold Medal ', 'p01.png', '80.00'),
(2, 'PD02', 'Tito\'s Handmade Vodka ', 'USA- Designed to be savored by spirit connoisseurs. Micro-distilled in an old-fashioned pot still to provide more control over the distillation process and resulting in a spectacularly clean product. Only the heart of the run is taken, leaving behind resi', 'p02.png', '88.00'),
(3, 'PD03', 'Glenlivet 12 Yr ', 'Speyside, Scotland- Aromas are sweet and smoky with citrus, campfire and honeysuckle. Slightly sweet in the mouth, the texture is round and soft. Warm spice flavors are layered with vanilla, oatmeal cookies, and wood coals. Mild and smooth. GOLD MEDAL - 2', 'p03.png', '95.00'),
(4, 'PD04', 'Chivas Regal ', 'Wine Enthusiast -Scotland- Dry grainy notes with background scents of dried flowers and parchment. Entry is oily, buttery and semisweet; the whisky dazzles at midpalate with layered flavors of brown butter, cooking oil and barley malt. Finishes smoky, sat', 'p04.png', '100.00'),
(5, 'PD05', 'Blanton\'s ', 'Kentucky, USA- Spicy aromas of dried citrus and orange peels with a hint of grassy rye and pepper. Overall full, soft and mellow. A classic treat, balanced with citrus, sweet grains, sugar and spice. One of the first specialty Bourbons.', 'p05.png', '108.00'),
(6, 'PD06', 'Jack Daniels Black', 'Tennessee- Sour mash whiskey made in Tennessee from natural corn, rye, barley malt and then charcoal filtered. Sweet with caramel, vanilla and charred wood. Pure, clean, and smooth. Classic. Enjoy on the rocks or in mixed drinks', 'p06.png', '110.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(6) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) NOT NULL,
  `contactnum` varchar(30) NOT NULL,
  `streetAddress` varchar(200) NOT NULL,
  `unitNumber` varchar(20) NOT NULL,
  `postal` varchar(10) NOT NULL,
  `birthday` varchar(20) NOT NULL,
  `profile_pic` varchar(200) DEFAULT NULL,
  `google_auth_code` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `email`, `password`, `firstname`, `lastname`, `contactnum`, `streetAddress`, `unitNumber`, `postal`, `birthday`, `profile_pic`, `google_auth_code`) VALUES
(3000, 'mingkang', 'mingkang@lol.com', '388b63181193da31300b0168e391ccc6ddf12dbbb54be7da2f22cf0e6ed0a3e8', 'ming', 'kang', '91231112', 'bedok south avenue 3', '03-456', '123456', '1994-09-08', NULL, 'ZVKJADOWXR66K6NL');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`product_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `uid` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
