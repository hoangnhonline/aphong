-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2018 at 11:53 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chantrau_xy_4142`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `facebook_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `key_reset` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fullname`, `email`, `gender`, `phone`, `username`, `password`, `valid_from`, `valid_to`, `type`, `created_at`, `updated_at`, `last_login`, `status`, `facebook_id`, `image_url`, `key_reset`) VALUES
(1, 'Út Hoàng', 'hoangnhonline@gmail.com', NULL, NULL, '', '', '2018-03-18', '2018-03-26', 0, '2018-03-26 15:41:31', '2018-03-26 16:05:24', '0000-00-00 00:00:00', 1, 2147483647, 'https://scontent.xx.fbcdn.net/v/t1.0-1/p200x200/19598946_1079663028832359_7932678815044683219_n.jpg?_nc_cat=0&oh=20a27d5d11d63dc8c9898f921e693f81&oe=5B3D4874', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_video`
--

CREATE TABLE `data_video` (
  `id` bigint(20) NOT NULL,
  `origin_url` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `data_video`
--

INSERT INTO `data_video` (`id`, `origin_url`, `code`, `customer_id`, `created_at`, `updated_at`) VALUES
(1, 'https://streamable.com/54m41', '9ecd536955c76c2769596bdc6a468497', NULL, '2018-03-26 16:02:13', '2018-03-26 16:02:13'),
(4, 'https://streamable.com/54m41', '84642fe423cb1a983b4c13f39d1200b0', 1, '2018-03-26 16:05:13', '2018-03-26 16:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `changed_password` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(255) NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `status`, `changed_password`, `remember_token`, `created_user`, `updated_user`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$iDdOWGaKaATi2Cv5jLE1DOQm4WrYmB4yb7veqto0lH6OjqFxoUDBS', 3, 1, 0, 'FPNJmUz9zXMPwrPdHZazbuZrqtg1Mv3a14IANUkFsqVFulOkF3rB19KF9oLB', 1, 1, '2016-08-27 05:26:18', '2018-03-22 17:48:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `full_name` (`fullname`),
  ADD KEY `type` (`type`),
  ADD KEY `image_url` (`image_url`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `data_video`
--
ALTER TABLE `data_video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `origin_url` (`origin_url`,`customer_id`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `data_video`
--
ALTER TABLE `data_video`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
