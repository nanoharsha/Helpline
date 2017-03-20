-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2016 at 12:33 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `issue_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(60) DEFAULT NULL,
  `description` text,
  `priority` char(2) DEFAULT 'N',
  `filename` varchar(255) DEFAULT NULL,
  `filetype` varchar(30) DEFAULT NULL,
  `filedata` mediumblob,
  `task_done` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL,
  `title` varchar(60) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `issue_type_id` int(11) DEFAULT NULL,
  `issue_type` varchar(255) DEFAULT NULL,
  `description` text,
  `contact_person` varchar(60) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `priority` char(2) DEFAULT 'N',
  `filename` varchar(255) DEFAULT NULL,
  `filetype` varchar(30) DEFAULT NULL,
  `filedata` mediumblob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `issue_types`
--

CREATE TABLE `issue_types` (
  `id` int(11) NOT NULL,
  `issue_type` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `issue_types`
--

INSERT INTO `issue_types` (`id`, `issue_type`) VALUES
(1, 'Issue Type 1'),
(2, 'Issue Type 2'),
(3, 'This is coming from DB'),
(4, 'Issue Types are stored in table in DB :)'),
(5, 'These values are read from there');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `filetype` varchar(60) DEFAULT NULL,
  `filedata` mediumblob,
  `done` char(2) DEFAULT 'N',
  `priority` char(2) DEFAULT 'N',
  `description` text,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_id` (`issue_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_type_id` (`issue_type_id`);

--
-- Indexes for table `issue_types`
--
ALTER TABLE `issue_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `issue_id` (`issue_id`);
