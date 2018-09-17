-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 184.168.45.190
-- Generation Time: Sep 17, 2018 at 05:45 AM
-- Server version: 5.0.96
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bedldartstats`
--

-- --------------------------------------------------------

--
-- Table structure for table `weeks`
--

CREATE TABLE `weeks` (
  `week` int(11) NOT NULL,
  `date` date default NULL,
  PRIMARY KEY  (`week`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `weeks`
--

INSERT INTO `weeks` VALUES(1, '2018-03-22');
INSERT INTO `weeks` VALUES(2, '2018-03-29');
INSERT INTO `weeks` VALUES(3, '2018-04-05');
INSERT INTO `weeks` VALUES(4, '2018-04-12');
INSERT INTO `weeks` VALUES(5, '2018-04-19');
INSERT INTO `weeks` VALUES(6, '2018-04-26');
INSERT INTO `weeks` VALUES(7, '2018-05-03');
INSERT INTO `weeks` VALUES(8, '2018-05-10');
INSERT INTO `weeks` VALUES(9, '2018-05-17');
INSERT INTO `weeks` VALUES(10, '2018-05-24');
INSERT INTO `weeks` VALUES(11, '2018-05-31');
INSERT INTO `weeks` VALUES(12, '2018-06-07');
INSERT INTO `weeks` VALUES(13, '2018-06-14');
INSERT INTO `weeks` VALUES(14, '2018-06-21');
INSERT INTO `weeks` VALUES(15, '2018-07-05');
INSERT INTO `weeks` VALUES(16, '2018-07-12');
INSERT INTO `weeks` VALUES(17, '2018-07-19');
