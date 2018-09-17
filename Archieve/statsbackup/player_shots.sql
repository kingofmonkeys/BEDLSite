-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 184.168.45.190
-- Generation Time: Sep 17, 2018 at 05:42 AM
-- Server version: 5.0.96
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bedldartstats`
--

-- --------------------------------------------------------

--
-- Table structure for table `player_shots`
--

CREATE TABLE `player_shots` (
  `player_id` int(11) NOT NULL,
  `week_number` int(2) NOT NULL,
  `shotId` mediumint(9) default NULL,
  `shotvalue` varchar(50) default NULL,
  `normshotvalue` varchar(50) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `player_shots`
--

INSERT INTO `player_shots` VALUES(142, 1, 1, '180', '180');
INSERT INTO `player_shots` VALUES(219, 2, 1, '180', '180');
INSERT INTO `player_shots` VALUES(193, 3, 4, '5', '5');
INSERT INTO `player_shots` VALUES(21, 3, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(213, 3, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(197, 3, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(194, 4, 5, NULL, NULL);
INSERT INTO `player_shots` VALUES(44, 5, 1, '171', '171');
INSERT INTO `player_shots` VALUES(101, 3, 3, '94', '094');
INSERT INTO `player_shots` VALUES(122, 3, 3, '92', '092');
INSERT INTO `player_shots` VALUES(219, 6, 4, '6', '6');
INSERT INTO `player_shots` VALUES(3, 6, 3, '112', '112');
INSERT INTO `player_shots` VALUES(194, 8, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(219, 8, 1, '171', '171');
INSERT INTO `player_shots` VALUES(219, 8, 6, 'x2', NULL);
INSERT INTO `player_shots` VALUES(84, 9, 6, 'x2', NULL);
INSERT INTO `player_shots` VALUES(122, 9, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(193, 9, 3, '112', '112');
INSERT INTO `player_shots` VALUES(219, 10, 3, '120', '120');
INSERT INTO `player_shots` VALUES(198, 10, 4, '7', '7');
INSERT INTO `player_shots` VALUES(35, 10, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(194, 10, 1, '180 x2', '180');
INSERT INTO `player_shots` VALUES(3, 11, 1, '180 x2', '180');
INSERT INTO `player_shots` VALUES(194, 11, 4, '5', '5');
INSERT INTO `player_shots` VALUES(183, 11, 1, '174', '174');
INSERT INTO `player_shots` VALUES(183, 11, 1, '180', '180');
INSERT INTO `player_shots` VALUES(193, 11, 6, 'x2', NULL);
INSERT INTO `player_shots` VALUES(202, 11, 4, '7', '7');
INSERT INTO `player_shots` VALUES(84, 12, 1, '180 x2', '180');
INSERT INTO `player_shots` VALUES(209, 12, 4, '6', '6');
INSERT INTO `player_shots` VALUES(202, 12, 1, '180 x2', '180');
INSERT INTO `player_shots` VALUES(202, 12, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(234, 10, 1, '180 x2', '180');
INSERT INTO `player_shots` VALUES(213, 13, 1, '180 x3', '180');
INSERT INTO `player_shots` VALUES(193, 13, 3, '135', '135');
INSERT INTO `player_shots` VALUES(226, 13, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(3, 14, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(3, 14, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(44, 14, 3, '95', '095');
INSERT INTO `player_shots` VALUES(24, 15, 3, '92', '92');
INSERT INTO `player_shots` VALUES(193, 15, 1, '171', '171');
INSERT INTO `player_shots` VALUES(226, 15, 1, '180', '180');
INSERT INTO `player_shots` VALUES(194, 15, 6, NULL, NULL);
INSERT INTO `player_shots` VALUES(193, 17, 4, '9', '9');
