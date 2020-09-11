-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 11, 2020 at 02:46 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tree`
--
CREATE DATABASE IF NOT EXISTS `tree` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `tree`;

-- --------------------------------------------------------

--
-- Table structure for table `tree_table`
--

DROP TABLE IF EXISTS `tree_table`;
CREATE TABLE `tree_table` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `text` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tree_table`
--

INSERT INTO `tree_table` (`id`, `parent_id`, `text`) VALUES
(1, 0, 'Root'),
(2, 1, 'Root'),
(3, 2, 'Root'),
(4, 3, 'Root'),
(5, 0, 'Root'),
(6, 0, 'Root'),
(7, 2, 'Root'),
(8, 2, 'Root'),
(9, 1, 'Root'),
(10, 1, 'Root'),
(11, 5, 'Root'),
(12, 5, 'Root'),
(13, 4, 'Root'),
(14, 13, 'Root'),
(15, 14, 'Root');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tree_table`
--
ALTER TABLE `tree_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tree_table`
--
ALTER TABLE `tree_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
