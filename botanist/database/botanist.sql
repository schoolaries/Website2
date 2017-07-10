-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2017 at 03:04 PM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `botanist`
--

-- --------------------------------------------------------

--
-- Table structure for table `creditcard`
--

CREATE TABLE `creditcard` (
  `CardID` int(11) NOT NULL,
  `CreditCardNo` varchar(500) NOT NULL,
  `CnumHash` varchar(64) NOT NULL,
  `Lastfour` int(5) NOT NULL,
  `UserID` varchar(15) NOT NULL,
  `CreditCardType` varchar(11) NOT NULL,
  `Billing_address` varchar(100) NOT NULL,
  `Postal` int(11) NOT NULL,
  `CardExpiryDate` date NOT NULL,
  `CVNo` varchar(500) NOT NULL,
  `CVHash` varchar(64) NOT NULL,
  `IssuingBank` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `creditcard`
--

INSERT INTO `creditcard` (`CardID`, `CreditCardNo`, `CnumHash`, `Lastfour`, `UserID`, `CreditCardType`, `Billing_address`, `Postal`, `CardExpiryDate`, `CVNo`, `CVHash`, `IssuingBank`) VALUES
(5, '181712d85736bce0a6571181b51b5ebe75dd5c2a10f5e2cacc9107b1676e0a21', 'bPuB3Md/OUerpZGyH8bh57aB1p5JmQj+rA4liC9mmvE=', 3456, '589d7a6acb03b', 'Visa', '3wzxectuvyibuoinomp,erhiofdkjsvcx cuwFVOIBSPANO', 234567, '2019-07-11', '6632b625f7fe306cfd530244107c92c470ccf73fcdf7105970ff6c1e8b713560', 'k7zRbGSl4kwAGHSJsu1MeY9e+vvbb6nKyBUJBcdrODU=', 'DBS'),
(6, '3fb2b4bcb52715278dccc8c4d5c622e9f6f5d767f322e168331c82108756569c', 'SGR1FjQmQMgDr0vVwH6EfJe+AS1U0VZ/86I+4UKuG/w=', 3456, '589d7bf2b1f01', 'Visa', 'xe5urcywitvubinompewfsd', 567893, '2017-12-14', 'fb82d59668f35359f4ce114031bfc92378aa71fc43cfb9a67792a60d93809cb6', '/AY2MgKr0isopJvZDD1qSqlTQPFvjc+OTx0vZfjfC/s=', 'POSB'),
(8, '2fbe9e1104b27d8a98cfcf11bb775737cf4ee8838192d802a78105578fdb29b9', 'YXVDXIyekvcHbI9DRD1c6wOgecAEOQHG0UMCMWwB61w=', 6789, '589e9aa26842c', 'Visa', 'e5r6tvybnuiom', 345677, '2020-07-03', '6a59b289fdc3f61dff9d57345c4b42c31aed427898308c67a0c5da54c882e260', 'zoi4uGkxv1wKej53597rMhX+ZFCq9WFzNT06eDAHE3o=', 'POSVB'),
(9, 'c192a6d6d83a807cd32d096b49ade168480dc8affa45148d2750ed110d3415ac', 'pDUqepymyhHnP1CXzLLV/striBLv+xIlGHLvyxkVlUE=', 3123, '589f19e44b6c8', 'Visa', 'Istana', 123456, '2020-04-02', '19e4203e467e29902ba7a19dbf1e394bf5f35a05f8eb8ac5147c22009e844c91', 'JenhEGgibXYPKOpR84HK+t8CvaUpMpIxJGjtnvuZN8o=', 'MAS');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `UserID` varchar(20) NOT NULL,
  `Attempts` int(11) NOT NULL,
  `LastLogin` datetime NOT NULL,
  `Activate` text NOT NULL,
  `FailAttempt` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`UserID`, `Attempts`, `LastLogin`, `Activate`, `FailAttempt`) VALUES
('589d7a6acb03b', 0, '2017-02-11 14:22:15', '', 4),
('589d7bf2b1f01', 0, '0000-00-00 00:00:00', '', 0),
('589e9a3493078', 0, '0000-00-00 00:00:00', '', 0),
('589e9aa26842c', 0, '0000-00-00 00:00:00', '', 0),
('589f19e44b6c8', 0, '0000-00-00 00:00:00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductCode` varchar(60) NOT NULL,
  `ProductName` varchar(60) NOT NULL,
  `ProductDesc` varchar(2000) NOT NULL,
  `ProductImgName` varchar(60) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductCode`, `ProductName`, `ProductDesc`, `ProductImgName`, `Price`) VALUES
(1, 'PD1001', 'Bouquet of Pink Roses', 'This bouquet of pink roses will melt your heart. ', 'rsz_rose4.png', '110.00'),
(2, 'PD1002', 'Bouquet of Pink Lilies', 'Pink and white lilies a perfect gift for a dainty lady', 'rsz_lilies2.png', '70.00'),
(3, 'PD1003', 'Bouquet of Sunflowers', 'Hold the sunshine within the grasp of your palm!', 'rsz_sunflower1.png', '82.00'),
(4, 'PD1004', 'Bouquet of  Tulips', 'Check out this captivating bouquet of 10 assorted tulips', 'rsz_tulip5.png', '94.00'),
(5, 'PD1005', 'Bouquet of blue orchids', 'Indulge in this fantasy of enchanting orchid blooms', 'rsz_orchid3.png', '88.01');

-- --------------------------------------------------------

--
-- Table structure for table `suggestion`
--

CREATE TABLE `suggestion` (
  `FeedbackID` int(11) NOT NULL,
  `Email` varchar(35) NOT NULL,
  `Comment` varchar(500) NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suggestion`
--

INSERT INTO `suggestion` (`FeedbackID`, `Email`, `Comment`, `Timestamp`) VALUES
(14, 'JJ@hotmail.com', 'UHGFDSBGVFDSA', '2016-02-25 04:22:16'),
(18, '', '', '2017-02-11 04:50:20'),
(19, '', '', '2017-02-11 04:50:20'),
(20, '', '', '2017-02-11 04:50:26'),
(21, '', '', '2017-02-11 04:50:26'),
(22, '', '', '2017-02-11 04:51:30'),
(23, '', '', '2017-02-11 04:51:30'),
(26, '', '', '2017-02-11 04:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` varchar(15) NOT NULL,
  `FirstName` varchar(20) NOT NULL,
  `LastName` varchar(20) NOT NULL,
  `Gender` char(1) NOT NULL,
  `Email` varchar(35) NOT NULL,
  `Username` varchar(35) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Hash` varchar(255) NOT NULL,
  `Contact` int(10) NOT NULL,
  `Birthdate` date NOT NULL,
  `Role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `FirstName`, `LastName`, `Gender`, `Email`, `Username`, `Password`, `Hash`, `Contact`, `Birthdate`, `Role`) VALUES
('589d7a6acb03b', 'Stamford', 'Raffles', 'm', 'dude@localhost.jj', 'minad', '70cddabb4613ece98b8f477d4056d3e30d2d65ed09e554c76fff9b9f295a09a7', '6Yw6ji2fmt57PotY05G3mbM8zYYqNQqJEykWMqS+aMM=', 82685555, '1929-07-23', 'Admin'),
('589d7bf2b1f01', 'John', 'Lim', 'm', 'aa@bb.cc', 'User1', '29de3201135f47a399e5d609b33f7f080a563187907ba0fea9f5fdfe35892264', 'VDAMbElGwQrXNBwxx/e6ulnnwSlVX9xOP+ZA16crZcE=', 81851255, '1997-07-03', 'Admin'),
('589e9aa26842c', 'bobo', 'huhuhaha', 'm', 'xrc6t7vy8bunimp@htdhe.sdf', 'User2', '500556e2e4f090edaac874b103b9f31c4d6463806286f30cbd92dea181800c78', 'uAdAb8TsXNJnfNLlnMga7Fgj1i3LouJ0vHU3irYw0gc=', 82685555, '1992-07-10', 'Member'),
('589f19e44b6c8', 'Hsien', 'Loong', 'f', 'pmlee@pap.sg', 'User3', '96e479423447d7dc18d3300414c49d976c62008c48f2d6a99d8f8ba83916545c', '3uc4aoWtdpxDWzblDGPN451aYDHVqMNYS2B5Zk6QH+4=', 99988877, '1980-07-10', 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `verification_database`
--

CREATE TABLE `verification_database` (
  `UserID` varchar(15) NOT NULL,
  `Timecreated` int(255) NOT NULL,
  `Secretkey` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `verification_database`
--

INSERT INTO `verification_database` (`UserID`, `Timecreated`, `Secretkey`) VALUES
('56c862292bb0d', 1455972905, 'jFodd8'),
('56c8707ae9730', 1456379930, '$2y$12$D86bGIpXaMqyL8LJDdeXhu.WKolISj9SDat9D7iZv3IuOlyH5sPBe'),
('56c88b3c5f59b', 1455983421, 'F6REH5'),
('56ce730a74574', 1456370443, '$2y$12$u1CtaDq7w69C9QM.IHfQwenhlpo0M32VqNocDI.4WdlrUmYtb4QC2'),
('56ceb334941aa', 1456387115, '$2y$12$To3aayTsTHqqbE6BJD79JuzhjdS9B6lmMZ22.13BZL7rrys45.SEe'),
('56cec729bae1e', 1456392124, '$2y$12$aO81Gvd2kxZoL.l5WK8pnuxLAEyBqN.P0DaV/NwReuJGg.JhIBsL2'),
('589d7a6acb03b', 1486783300, '$2y$12$W9UJS.e43uOq6ZgAdhq2hexM4YpoiFPt2ShSSey0yVhPP4HNG/OKS'),
('589d7a6acb03b', 1486783369, '$2y$12$lUNrrUusjFY0vOF/aa2TbO94EP980Jo5lmml/w7g0.yu5oZLv0Fki'),
('589d7a6acb03b', 1486783491, '$2y$12$aMmtilKAoDHuaewu7pyWNuEY2JkPcBP6YJZ5avPZaKTZ5tVmBgs4e'),
('589d7a6acb03b', 1486784261, 'i3pxh2'),
('589d7a6acb03b', 1486785104, 'rwQ2OL'),
('589d7a6acb03b', 1486799136, 'c2Rh1N'),
('589d7a6acb03b', 1486799433, 'hD0rv1'),
('589d7bf2b1f01', 1486800848, 'YBdmku');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`CardID`),
  ADD UNIQUE KEY `CreditCardNo` (`CreditCardNo`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD UNIQUE KEY `product_code` (`ProductCode`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `suggestion`
--
ALTER TABLE `suggestion`
  ADD PRIMARY KEY (`FeedbackID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `verification_database`
--
ALTER TABLE `verification_database`
  ADD KEY `UserID` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `creditcard`
--
ALTER TABLE `creditcard`
  MODIFY `CardID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `suggestion`
--
ALTER TABLE `suggestion`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `Bloop` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
