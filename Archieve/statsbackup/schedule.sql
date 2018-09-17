-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 184.168.45.190
-- Generation Time: Sep 17, 2018 at 05:43 AM
-- Server version: 5.0.96
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bedldartstats`
--

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `ID` mediumint(9) NOT NULL auto_increment,
  `week` int(11) NOT NULL,
  `hometeamid` int(11) NOT NULL,
  `visitingteamid` int(11) NOT NULL,
  `score_entered` varchar(10) NOT NULL default 'false',
  PRIMARY KEY  (`ID`),
  KEY `hometeamid` (`hometeamid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` VALUES(1, 1, 1, 4, 'true');
INSERT INTO `schedule` VALUES(2, 1, 7, 3, 'true');
INSERT INTO `schedule` VALUES(3, 1, 2, 6, 'true');
INSERT INTO `schedule` VALUES(4, 2, 4, 2, 'true');
INSERT INTO `schedule` VALUES(5, 2, 3, 5, 'true');
INSERT INTO `schedule` VALUES(6, 2, 6, 1, 'true');
INSERT INTO `schedule` VALUES(7, 3, 1, 3, 'true');
INSERT INTO `schedule` VALUES(8, 3, 6, 5, 'true');
INSERT INTO `schedule` VALUES(9, 3, 4, 7, 'true');
INSERT INTO `schedule` VALUES(10, 4, 5, 2, 'true');
INSERT INTO `schedule` VALUES(11, 4, 3, 4, 'true');
INSERT INTO `schedule` VALUES(12, 4, 7, 1, 'true');
INSERT INTO `schedule` VALUES(13, 5, 7, 2, 'true');
INSERT INTO `schedule` VALUES(14, 5, 3, 6, 'true');
INSERT INTO `schedule` VALUES(15, 5, 5, 1, 'true');
INSERT INTO `schedule` VALUES(16, 6, 2, 3, 'true');
INSERT INTO `schedule` VALUES(17, 6, 4, 5, 'true');
INSERT INTO `schedule` VALUES(18, 6, 6, 7, 'true');
INSERT INTO `schedule` VALUES(19, 7, 1, 2, 'true');
INSERT INTO `schedule` VALUES(20, 7, 7, 5, 'true');
INSERT INTO `schedule` VALUES(21, 7, 4, 6, 'true');
INSERT INTO `schedule` VALUES(22, 8, 4, 1, 'true');
INSERT INTO `schedule` VALUES(23, 8, 3, 7, 'true');
INSERT INTO `schedule` VALUES(24, 8, 6, 2, 'true');
INSERT INTO `schedule` VALUES(25, 9, 2, 4, 'true');
INSERT INTO `schedule` VALUES(26, 9, 5, 3, 'true');
INSERT INTO `schedule` VALUES(27, 9, 1, 6, 'true');
INSERT INTO `schedule` VALUES(28, 10, 3, 1, 'true');
INSERT INTO `schedule` VALUES(29, 10, 5, 6, 'true');
INSERT INTO `schedule` VALUES(30, 10, 7, 4, 'true');
INSERT INTO `schedule` VALUES(31, 11, 2, 5, 'true');
INSERT INTO `schedule` VALUES(32, 11, 4, 3, 'true');
INSERT INTO `schedule` VALUES(33, 11, 1, 7, 'true');
INSERT INTO `schedule` VALUES(34, 12, 2, 7, 'true');
INSERT INTO `schedule` VALUES(35, 12, 6, 3, 'true');
INSERT INTO `schedule` VALUES(36, 12, 1, 5, 'true');
INSERT INTO `schedule` VALUES(37, 13, 3, 2, 'true');
INSERT INTO `schedule` VALUES(38, 13, 5, 4, 'true');
INSERT INTO `schedule` VALUES(39, 13, 7, 6, 'true');
INSERT INTO `schedule` VALUES(40, 14, 2, 1, 'true');
INSERT INTO `schedule` VALUES(41, 14, 5, 7, 'true');
INSERT INTO `schedule` VALUES(42, 14, 6, 4, 'true');
INSERT INTO `schedule` VALUES(43, 1, 5, 8, 'true');
INSERT INTO `schedule` VALUES(44, 2, 8, 7, 'true');
INSERT INTO `schedule` VALUES(45, 3, 2, 8, 'true');
INSERT INTO `schedule` VALUES(46, 4, 8, 6, 'true');
INSERT INTO `schedule` VALUES(47, 5, 8, 4, 'true');
INSERT INTO `schedule` VALUES(48, 6, 1, 8, 'true');
INSERT INTO `schedule` VALUES(49, 7, 3, 8, 'true');
INSERT INTO `schedule` VALUES(50, 8, 8, 5, 'true');
INSERT INTO `schedule` VALUES(51, 9, 7, 8, 'true');
INSERT INTO `schedule` VALUES(52, 10, 8, 2, 'true');
INSERT INTO `schedule` VALUES(53, 11, 6, 8, 'true');
INSERT INTO `schedule` VALUES(54, 12, 4, 8, 'true');
INSERT INTO `schedule` VALUES(55, 13, 8, 1, 'true');
INSERT INTO `schedule` VALUES(56, 14, 8, 3, 'true');
INSERT INTO `schedule` VALUES(57, 15, 2, 4, 'true');
INSERT INTO `schedule` VALUES(58, 15, 5, 6, 'true');
INSERT INTO `schedule` VALUES(59, 15, 7, 8, 'true');
INSERT INTO `schedule` VALUES(60, 15, 3, 1, 'true');
INSERT INTO `schedule` VALUES(61, 16, 3, 4, 'true');
INSERT INTO `schedule` VALUES(62, 16, 5, 8, 'true');
INSERT INTO `schedule` VALUES(63, 16, 7, 6, 'true');
INSERT INTO `schedule` VALUES(64, 16, 2, 1, 'true');
INSERT INTO `schedule` VALUES(65, 17, 3, 2, 'true');
INSERT INTO `schedule` VALUES(66, 17, 4, 1, 'true');
INSERT INTO `schedule` VALUES(67, 17, 7, 5, 'true');
INSERT INTO `schedule` VALUES(68, 17, 6, 8, 'true');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`hometeamid`) REFERENCES `teams` (`teamid`);
