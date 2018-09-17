-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 184.168.45.190
-- Generation Time: Sep 17, 2018 at 05:41 AM
-- Server version: 5.0.96
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bedldartstats`
--

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` int(11) NOT NULL auto_increment,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) default NULL,
  `division` varchar(2) NOT NULL,
  `team_id` varchar(100) default NULL,
  `rank` varchar(5) default NULL,
  PRIMARY KEY  (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=237 ;

--
-- Dumping data for table `players`
--

INSERT INTO `players` VALUES(3, 'John', 'Downes', '1', '2', '1');
INSERT INTO `players` VALUES(14, 'Chris', 'K', '2', '8', '2');
INSERT INTO `players` VALUES(19, 'Howard', 'B', '2', '3', '2');
INSERT INTO `players` VALUES(21, 'Nick', 'C', '2', '4', '2');
INSERT INTO `players` VALUES(24, 'Mike', 'Darby', '2', '6', '2');
INSERT INTO `players` VALUES(25, 'Mike', 'Jones', '3', '6', '3');
INSERT INTO `players` VALUES(29, 'Thornie', 'P', '2', '0', '2');
INSERT INTO `players` VALUES(35, 'Mike', 'Lantz', '1', '5', '1');
INSERT INTO `players` VALUES(36, 'Donna', 'L', '3', '5', '4');
INSERT INTO `players` VALUES(37, 'Tina', 'G', '3', '5', '3');
INSERT INTO `players` VALUES(38, 'Mel', 'F', '3', '5', '3');
INSERT INTO `players` VALUES(39, 'Dana', 'W', '2', '1', '2');
INSERT INTO `players` VALUES(40, 'Dan', 'N', '3', '1', '3');
INSERT INTO `players` VALUES(41, 'Kim', 'G', '3', '1', '4');
INSERT INTO `players` VALUES(44, 'Mike', 'Webster', '2', '1', '2');
INSERT INTO `players` VALUES(45, 'John', 'Moore', '3', '7', '3');
INSERT INTO `players` VALUES(51, 'Fred', 'J', '2', '2', '2');
INSERT INTO `players` VALUES(65, 'Jimmy', 'C', '3', '7', '3');
INSERT INTO `players` VALUES(67, 'Heather', NULL, '3', '6', '4');
INSERT INTO `players` VALUES(84, 'Dave', 'Bonham', '1', '2', '1');
INSERT INTO `players` VALUES(101, 'Charlie', 'S', '3', '7', '3');
INSERT INTO `players` VALUES(122, 'Tie Die', NULL, '2', '1', '2');
INSERT INTO `players` VALUES(142, 'Kelli', NULL, '2', '6', '2');
INSERT INTO `players` VALUES(150, 'Bill', 'K', '2', '0', '2');
INSERT INTO `players` VALUES(183, 'Jeff', 'B', '1', '7', '1');
INSERT INTO `players` VALUES(188, 'Howard', 'Laisure', '2', '2', '2');
INSERT INTO `players` VALUES(193, 'Mike', 'Lagana', '1', '3', '1');
INSERT INTO `players` VALUES(194, 'Chuck', 'D', '1', '1', '1');
INSERT INTO `players` VALUES(197, 'Greg', 'O', '2', '4', '2');
INSERT INTO `players` VALUES(198, 'Jimmy', 'O', '2', '4', '2');
INSERT INTO `players` VALUES(200, 'Missy', 'M', '3', '7', '4');
INSERT INTO `players` VALUES(201, 'Daryl', 'J', '1', '3', '1');
INSERT INTO `players` VALUES(202, 'Lee', 'F', '1', '3', '1');
INSERT INTO `players` VALUES(204, 'Travis', NULL, '2', '4', '2');
INSERT INTO `players` VALUES(207, 'Tim', 'Stohl', '1', '', '1');
INSERT INTO `players` VALUES(208, 'John', 'Jay Jr.', '2', '0', '2');
INSERT INTO `players` VALUES(209, 'Erica', 'Drake', '3', '5', '3');
INSERT INTO `players` VALUES(211, 'George', 'R', '2', '6', '2');
INSERT INTO `players` VALUES(212, 'Nick', 'Hicks', '3', '0', '3');
INSERT INTO `players` VALUES(213, 'Dre', NULL, '1', '3', '1');
INSERT INTO `players` VALUES(214, 'Juliana', 'O', '3', '4', '3');
INSERT INTO `players` VALUES(215, 'Steve', 'H', '2', NULL, '2');
INSERT INTO `players` VALUES(216, 'Chris', 'M', '3', NULL, '3');
INSERT INTO `players` VALUES(218, 'Nick', 'J', '3', NULL, '3');
INSERT INTO `players` VALUES(219, 'Tom', 'Conrad Jr.', '1', '4', '1');
INSERT INTO `players` VALUES(220, 'Tom', 'Anders', '2', '6', '2');
INSERT INTO `players` VALUES(221, 'Oscar', 'Ross', '3', '8', '3');
INSERT INTO `players` VALUES(222, 'Paul', NULL, '3', '8', '4');
INSERT INTO `players` VALUES(223, 'Anthony', NULL, '3', '8', '3');
INSERT INTO `players` VALUES(224, 'John', 'K', '3', '8', '4');
INSERT INTO `players` VALUES(225, 'Mike', 'F', '3', '8', '3');
INSERT INTO `players` VALUES(226, 'Joe', 'L', '1', '3', '1');
INSERT INTO `players` VALUES(227, 'Doug', 'P', '2', '8', '2');
INSERT INTO `players` VALUES(232, 'Debi', 'Lambros', '3', '0', '3');
INSERT INTO `players` VALUES(233, 'Tom', 'Conrad Sr.', '3', '0', '3');
INSERT INTO `players` VALUES(234, 'Lee', 'B', '1', '2', '1');
INSERT INTO `players` VALUES(235, 'Kristen', 'B', '2', NULL, '2');
INSERT INTO `players` VALUES(236, 'Rick', 'Johnson', '3', '8', '3');
