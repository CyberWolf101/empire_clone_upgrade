-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 20, 2023 at 01:12 AM
-- Server version: 10.3.39-MariaDB
-- PHP Version: 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oshofree_luxury`
--

-- --------------------------------------------------------

--
-- Table structure for table `admob`
--

CREATE TABLE `rental_center` (
  `s` int(10) UNSIGNED NOT NULL,
  `rental_id` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `dateregistered` varchar(45) NOT NULL,
	`datetouse` varchar(45) NOT NULL,
  `firstreason` varchar(45) NOT NULL,
  `secondreason` varchar(45) NOT NULL,
  `duration` varchar(45) NOT NULL,
	 `people` varchar(45) NOT NULL,
  `confirmation` varchar(45) NOT NULL,
  `rentalhistory` varchar(45) NOT NULL ,
	PRIMARY KEY  (`s`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admob`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `admob`
--
