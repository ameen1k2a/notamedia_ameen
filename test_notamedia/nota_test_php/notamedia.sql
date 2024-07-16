-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2024 at 04:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `notamedia`
--

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `script_name` varchar(25) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `result` enum('normal','illegal','failed','success') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `script_name`, `start_time`, `end_time`, `result`) VALUES
(1, 'script17', '2024-07-15 16:21:12', '2024-07-16 08:21:12', 'failed'),
(2, 'script9', '2024-07-01 16:21:12', '2024-07-02 10:21:12', 'illegal'),
(3, 'script17', '2024-07-13 16:21:12', '2024-07-14 00:21:12', 'normal'),
(4, 'script2', '2024-06-19 16:21:12', '2024-06-19 20:21:12', 'success'),
(5, 'script3', '2024-07-13 16:21:12', '2024-07-14 02:21:12', 'failed'),
(6, 'script10', '2024-06-30 16:21:12', '2024-06-30 23:21:12', 'success'),
(7, 'script4', '2024-06-24 16:21:12', '2024-06-25 01:21:12', 'normal'),
(8, 'script4', '2024-07-10 16:21:12', '2024-07-11 15:21:12', 'success'),
(9, 'script10', '2024-06-21 16:21:12', '2024-06-21 21:21:12', 'success'),
(10, 'script17', '2024-07-14 16:21:12', '2024-07-14 22:21:12', 'failed');

-- --------------------------------------------------------

--
-- Table structure for table `wiki_sections`
--

CREATE TABLE `wiki_sections` (
  `id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `title` varchar(230) NOT NULL,
  `url` varchar(240) NOT NULL,
  `picture` varchar(240) DEFAULT NULL,
  `abstract` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wiki_sections`
--

INSERT INTO `wiki_sections` (`id`, `date_created`, `title`, `url`, `picture`, `abstract`) VALUES
(1, '2024-07-16 16:16:15', '\n\nWikipedia\n\nThe Free Encyclopedia\n', '//en.wikipedia.org/', 'portal/wikipedia.org/assets/img/Wikipedia-logo-v2.png', '\nSave your favorite articles to read offline, sync your reading lists across devices and customize your reading experience with the official Wikipedia app.\n'),
(2, '2024-07-16 16:16:15', 'The Free Encyclopedia', '//ja.wikipedia.org/', NULL, '\nThis page is available under the Creative Commons Attribution-ShareAlike License\nTerms of Use\nPrivacy Policy\n'),
(3, '2024-07-16 16:16:15', 'English', '//de.wikipedia.org/', NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wiki_sections`
--
ALTER TABLE `wiki_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`),
  ADD UNIQUE KEY `picture` (`picture`),
  ADD UNIQUE KEY `abstract` (`abstract`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wiki_sections`
--
ALTER TABLE `wiki_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
