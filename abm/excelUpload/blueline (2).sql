-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2018 at 03:23 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blueline`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zip` varchar(100) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `main_email` varchar(200) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `payment_terms` varchar(100) DEFAULT NULL,
  `is_deleted` tinyint(10) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE IF NOT EXISTS `customer_types` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `customer_type` varchar(100) DEFAULT NULL,
  `discount` varchar(100) DEFAULT NULL,
  `distributor_price` decimal(10,2) NOT NULL,
  `dealer_price` decimal(10,2) NOT NULL,
  `agency_price` decimal(10,2) NOT NULL,
  `is_deleted` tinyint(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer_users`
--

CREATE TABLE IF NOT EXISTS `customer_users` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email_address` varchar(255) DEFAULT NULL,
  `company_id` int(45) DEFAULT NULL,
  `customer_type_id` int(45) DEFAULT NULL,
  `is_deleted` tinyint(10) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `type` enum('type1','type2') DEFAULT NULL,
  `category` enum('cat1','cat2') DEFAULT NULL,
  `part_no` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `cost_parts` decimal(10,2) DEFAULT NULL,
  `cost_labour` decimal(10,2) DEFAULT NULL,
  `list_price` decimal(10,2) DEFAULT NULL,
  `is_deleted` tinyint(10) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `type`, `category`, `part_no`, `description`, `cost_parts`, `cost_labour`, `list_price`, `is_deleted`, `created_at`, `updated_at`) VALUES
(2, 'type2', 'cat2', 'part no', 'desc', '200.00', '200.00', '200.00', 0, '2018-02-13 04:47:20', '2018-02-13 05:04:35'),
(3, 'type2', 'cat2', 'asas', 'asas', '1020.00', '1000.00', '10.00', 0, '2018-02-13 04:48:24', '2018-02-13 05:09:34'),
(4, 'type2', 'cat1', '00019', 'Description', '400.00', '400.00', '400.00', 0, '2018-02-13 04:51:01', '2018-02-13 04:51:01'),
(5, 'type1', 'cat2', '131', 'sdsd', '1323.23', '23.23', '2323.23', 0, '2018-02-13 04:59:05', '2018-02-13 04:59:05'),
(6, 'type1', 'cat1', 'Part now', 'des', '1900.00', '1900.00', '1900.00', 0, '2018-02-13 05:40:58', '2018-02-13 05:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `quote_invoice`
--

CREATE TABLE IF NOT EXISTS `quote_invoice` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `quote_invoice_no` varchar(200) NOT NULL,
  `quote_invoice` varchar(200) NOT NULL,
  `customer_id` int(45) NOT NULL,
  `customer_user_id` int(45) NOT NULL,
  `quote_date` datetime DEFAULT NULL,
  `quote_by` varchar(200) DEFAULT NULL,
  `invoice_date` datetime DEFAULT NULL,
  `invoice_by` varchar(200) DEFAULT NULL,
  `payment_terms` varchar(200) DEFAULT NULL,
  `discounts` varchar(200) NOT NULL,
  `shipping` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `quote_invoice_details`
--

CREATE TABLE IF NOT EXISTS `quote_invoice_details` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `quote_invoice_id` int(45) NOT NULL,
  `product _id` int(45) NOT NULL,
  `qty` int(45) DEFAULT NULL,
  `added_by` varchar(200) DEFAULT NULL,
  `added_on` timestamp NULL DEFAULT NULL,
  `is_deleted` int(10) NOT NULL,
  `deleted_by` varchar(200) NOT NULL,
  `deleted_on` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `id` int(45) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(10) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `first_name`, `last_name`, `email_address`, `password`, `is_deleted`, `created_at`, `updated_at`) VALUES
(2, 'sdsd', 'sdsd', 'sdsd@gmail.com', '$2y$10$TwMMHdUp4TksZ3hDTWbrguE47/7Os03Zg.8Q8EKTx/3Mz7Wyo90vq', 0, '2018-02-12 05:48:22', '2018-02-12 05:48:22'),
(3, 'asa', 'sdsd', 'sd@gmail.coms', '$2y$10$UK5q6Djbakre1uUJ1pUfo.fOsuIm.u3nxs6TTHdUps3DcqX1BTtzG', 0, '2018-02-12 05:49:40', '2018-02-13 05:11:56'),
(4, 'Jomarie', 'Yllna', 'jom@gmail.com', '$2y$10$pp8L5gXpqwZW3Sle3c.KUe0r2IGnbnTZQ5NeIA1GQCYkQMUdATpMC', 0, '2018-02-12 05:50:10', '2018-02-13 03:24:14'),
(5, 'sdsdsd', 'sdsd', 'sdsd@gmail.com', '$2y$10$v5BOJeUzBxx6R7CQJsu8VOwbTUt2cjtsy88Vo/SfRY8cWJEvnBike', 0, '2018-02-12 05:55:43', '2018-02-12 05:55:43'),
(6, 'sdsd', 'sd', 'dsdsd@gmail.com', '$2y$10$2aS49gdFB4MOMlzqZul25ukR2qGTe8iEBC6GFmCRNyIJtQwW3lwri', 0, '2018-02-12 06:03:14', '2018-02-12 06:03:14'),
(7, 'sdsd', 'sdsd', 'sdsd@gmail.com', '$2y$10$zQeY3aEbttswMy0ulm0lUeh7Q5SzQkM4YptbwJGSO8btECFP78AqG', 0, '2018-02-13 00:32:47', '2018-02-13 00:32:47'),
(8, 'Judy Ann', 'Santos', 'santos@gmail.com', '$2y$10$yyKqBc.PHsTam01fwxxSzuX55qTTmQO14yFOgCP9JTDoIoT7XtWTa', 0, '2018-02-13 00:46:12', '2018-02-13 00:46:12'),
(9, 'test', 'sdsdsd', NULL, '$2y$10$XrMi.iz.EX2UVtzY3ROgAeRvKmg.EfwvIO.hMpp/Cds1Sc08tdeFS', 0, '2018-02-13 02:38:53', '2018-02-13 02:38:53'),
(10, 'test 111', 'aas1', 'sdsdd@gmai.cm', '$2y$10$mD.DzA3AT6aceaQQos6f/eTAPMF9SaKflAa3NMhP.Qz/XAjnG1yiy', 0, '2018-02-13 02:39:34', '2018-02-13 02:39:34'),
(11, 'Bridgett Aikee', 'Lloren', 'lloren@gmail.com', '$2y$10$ATfwlRy2SkdMZVr3RgoJHeRBAayiXNFFERWflSQrBMV.kDQLCrb82', 0, '2018-02-13 05:05:37', '2018-02-13 05:12:17'),
(12, 'Bir', 'dis', 'bird@gmail.com', NULL, 0, '2018-02-13 05:12:36', '2018-02-13 05:12:36');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
