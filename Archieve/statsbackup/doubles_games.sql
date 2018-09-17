-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 184.168.45.190
-- Generation Time: Sep 17, 2018 at 05:40 AM
-- Server version: 5.0.96
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bedldartstats`
--

-- --------------------------------------------------------

--
-- Table structure for table `doubles_games`
--

CREATE TABLE `doubles_games` (
  `game_id` int(11) NOT NULL auto_increment,
  `week` int(11) NOT NULL,
  `game_type` int(11) NOT NULL,
  `home_team_id` int(4) NOT NULL,
  `visit_team_id` int(4) NOT NULL,
  `home_player1_id` int(11) NOT NULL,
  `home_player2_id` int(11) NOT NULL,
  `home_wins` int(11) NOT NULL,
  `visit_player1_id` int(11) NOT NULL,
  `visit_player2_id` int(11) NOT NULL,
  `visit_wins` int(11) NOT NULL,
  PRIMARY KEY  (`game_id`),
  UNIQUE KEY `game_id` (`game_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=305 ;

--
-- Dumping data for table `doubles_games`
--

INSERT INTO `doubles_games` VALUES(1, 1, 1, 7, 3, 65, 221, 0, 193, 213, 2);
INSERT INTO `doubles_games` VALUES(2, 1, 1, 7, 3, 45, 183, 1, 202, 201, 2);
INSERT INTO `doubles_games` VALUES(3, 1, 2, 7, 3, 183, 45, 0, 202, 213, 2);
INSERT INTO `doubles_games` VALUES(4, 1, 2, 7, 3, 101, 65, 0, 193, 19, 2);
INSERT INTO `doubles_games` VALUES(5, 1, 1, 1, 4, 194, 39, 0, 214, 219, 2);
INSERT INTO `doubles_games` VALUES(6, 1, 1, 1, 4, 44, 122, 2, 204, 198, 1);
INSERT INTO `doubles_games` VALUES(7, 1, 2, 1, 4, 194, 39, 1, 214, 219, 2);
INSERT INTO `doubles_games` VALUES(8, 1, 2, 1, 4, 44, 122, 1, 204, 198, 2);
INSERT INTO `doubles_games` VALUES(9, 1, 1, 2, 6, 3, 188, 0, 142, 220, 2);
INSERT INTO `doubles_games` VALUES(10, 1, 1, 2, 6, 84, 207, 2, 24, 25, 1);
INSERT INTO `doubles_games` VALUES(11, 1, 2, 2, 6, 3, 188, 2, 142, 220, 0);
INSERT INTO `doubles_games` VALUES(12, 1, 2, 2, 6, 84, 207, 2, 24, 25, 0);
INSERT INTO `doubles_games` VALUES(13, 2, 1, 8, 7, 223, 222, 0, 183, 65, 2);
INSERT INTO `doubles_games` VALUES(14, 2, 1, 8, 7, 221, 224, 2, 101, 45, 0);
INSERT INTO `doubles_games` VALUES(15, 2, 2, 8, 7, 223, 222, 2, 183, 65, 1);
INSERT INTO `doubles_games` VALUES(16, 2, 2, 8, 7, 221, 224, 0, 101, 45, 2);
INSERT INTO `doubles_games` VALUES(17, 2, 1, 6, 1, 24, 25, 1, 194, 39, 2);
INSERT INTO `doubles_games` VALUES(18, 2, 1, 6, 1, 142, 220, 2, 122, 51, 0);
INSERT INTO `doubles_games` VALUES(19, 2, 2, 6, 1, 24, 25, 0, 194, 39, 2);
INSERT INTO `doubles_games` VALUES(20, 2, 2, 6, 1, 142, 220, 0, 122, 51, 2);
INSERT INTO `doubles_games` VALUES(21, 2, 1, 3, 5, 193, 19, 2, 35, 36, 1);
INSERT INTO `doubles_games` VALUES(22, 2, 1, 3, 5, 213, 201, 2, 37, 209, 0);
INSERT INTO `doubles_games` VALUES(23, 2, 2, 3, 5, 193, 19, 2, 35, 36, 0);
INSERT INTO `doubles_games` VALUES(24, 2, 2, 3, 5, 213, 201, 2, 37, 209, 0);
INSERT INTO `doubles_games` VALUES(25, 2, 1, 4, 2, 219, 198, 2, 3, 188, 1);
INSERT INTO `doubles_games` VALUES(26, 2, 1, 4, 2, 21, 204, 0, 84, 207, 2);
INSERT INTO `doubles_games` VALUES(27, 2, 2, 4, 2, 219, 198, 2, 3, 188, 0);
INSERT INTO `doubles_games` VALUES(28, 2, 2, 4, 2, 21, 204, 0, 84, 207, 2);
INSERT INTO `doubles_games` VALUES(29, 3, 1, 4, 7, 21, 197, 2, 101, 45, 1);
INSERT INTO `doubles_games` VALUES(30, 3, 1, 4, 7, 198, 219, 2, 65, 183, 0);
INSERT INTO `doubles_games` VALUES(31, 3, 2, 4, 7, 21, 197, 2, 101, 45, 0);
INSERT INTO `doubles_games` VALUES(32, 3, 2, 4, 7, 198, 219, 2, 183, 65, 0);
INSERT INTO `doubles_games` VALUES(33, 3, 1, 2, 8, 3, -2, 2, 224, 222, 1);
INSERT INTO `doubles_games` VALUES(34, 3, 1, 2, 8, 84, 188, 2, 223, 221, 0);
INSERT INTO `doubles_games` VALUES(35, 3, 2, 2, 8, 3, 188, 2, 223, 221, 1);
INSERT INTO `doubles_games` VALUES(36, 3, 2, 2, 8, 84, -2, 2, 222, 224, 0);
INSERT INTO `doubles_games` VALUES(37, 3, 1, 1, 3, 194, 39, 1, 193, 19, 2);
INSERT INTO `doubles_games` VALUES(38, 3, 1, 1, 3, 122, 44, 0, 202, 213, 2);
INSERT INTO `doubles_games` VALUES(39, 3, 2, 1, 3, 194, 39, 1, 213, 193, 2);
INSERT INTO `doubles_games` VALUES(40, 3, 2, 1, 3, 122, 44, 1, 202, 19, 2);
INSERT INTO `doubles_games` VALUES(41, 3, 1, 6, 5, 24, 25, 0, 38, 35, 2);
INSERT INTO `doubles_games` VALUES(42, 3, 1, 6, 5, 142, -2, 1, 209, 37, 2);
INSERT INTO `doubles_games` VALUES(43, 3, 2, 6, 5, 24, 25, 1, 209, 37, 2);
INSERT INTO `doubles_games` VALUES(44, 3, 2, 6, 5, 142, -2, 0, 38, 36, 2);
INSERT INTO `doubles_games` VALUES(45, 4, 1, 7, 1, 45, 101, 0, 194, 39, 2);
INSERT INTO `doubles_games` VALUES(46, 4, 1, 7, 1, 183, 65, 2, 44, 122, 1);
INSERT INTO `doubles_games` VALUES(47, 4, 2, 7, 1, 101, 65, 0, 194, 39, 2);
INSERT INTO `doubles_games` VALUES(48, 4, 2, 7, 1, 45, 183, 2, 122, 44, 1);
INSERT INTO `doubles_games` VALUES(49, 4, 1, 3, 4, 193, 226, 2, 204, 219, 0);
INSERT INTO `doubles_games` VALUES(50, 4, 1, 3, 4, 202, 201, 2, 197, 198, 0);
INSERT INTO `doubles_games` VALUES(51, 4, 2, 3, 4, 193, 226, 2, 204, 219, 0);
INSERT INTO `doubles_games` VALUES(52, 4, 2, 3, 4, 202, 201, 2, 197, 198, 1);
INSERT INTO `doubles_games` VALUES(53, 4, 1, 5, 2, 36, 209, 0, 3, 51, 2);
INSERT INTO `doubles_games` VALUES(54, 4, 1, 5, 2, 35, 38, 0, 84, 188, 2);
INSERT INTO `doubles_games` VALUES(55, 4, 2, 5, 2, 209, 37, 0, 3, 51, 2);
INSERT INTO `doubles_games` VALUES(56, 4, 2, 5, 2, 35, 38, 2, 84, 188, 1);
INSERT INTO `doubles_games` VALUES(57, 4, 1, 8, 6, 221, 227, 2, 142, 220, 0);
INSERT INTO `doubles_games` VALUES(58, 4, 1, 8, 6, 14, 14, 0, 24, 25, 2);
INSERT INTO `doubles_games` VALUES(59, 4, 2, 8, 6, 221, 227, 2, 142, 220, 0);
INSERT INTO `doubles_games` VALUES(60, 4, 2, 8, 6, 14, 14, 0, 24, 25, 2);
INSERT INTO `doubles_games` VALUES(61, 5, 1, 3, 6, 213, 193, 2, 24, 25, 0);
INSERT INTO `doubles_games` VALUES(62, 5, 1, 3, 6, 202, 226, 2, 142, 220, 1);
INSERT INTO `doubles_games` VALUES(63, 5, 2, 3, 6, 213, 193, 2, 24, 220, 0);
INSERT INTO `doubles_games` VALUES(64, 5, 2, 3, 6, 202, 226, 2, 25, 142, 0);
INSERT INTO `doubles_games` VALUES(65, 5, 1, 7, 2, 65, 183, 1, 3, 188, 2);
INSERT INTO `doubles_games` VALUES(66, 5, 1, 7, 2, 101, 45, 2, 84, 207, 1);
INSERT INTO `doubles_games` VALUES(67, 5, 2, 7, 2, 65, 183, 2, 3, 207, 1);
INSERT INTO `doubles_games` VALUES(68, 5, 2, 7, 2, 101, 45, 2, 84, 188, 0);
INSERT INTO `doubles_games` VALUES(69, 5, 1, 5, 1, 209, 37, 2, 44, 122, 1);
INSERT INTO `doubles_games` VALUES(70, 5, 1, 5, 1, 38, 35, 1, 194, 39, 2);
INSERT INTO `doubles_games` VALUES(71, 5, 2, 5, 1, 209, 37, 2, 44, 122, 1);
INSERT INTO `doubles_games` VALUES(72, 5, 2, 5, 1, 38, 35, 2, 194, 39, 0);
INSERT INTO `doubles_games` VALUES(73, 5, 1, 8, 4, 221, 14, 1, 197, 21, 2);
INSERT INTO `doubles_games` VALUES(74, 5, 1, 8, 4, 227, 227, 2, 198, 204, 0);
INSERT INTO `doubles_games` VALUES(75, 5, 2, 8, 4, 221, 14, 0, 197, 21, 2);
INSERT INTO `doubles_games` VALUES(76, 5, 2, 8, 4, 227, 227, 0, 198, 214, 2);
INSERT INTO `doubles_games` VALUES(77, 6, 1, 2, 3, 188, 235, 0, 193, 202, 2);
INSERT INTO `doubles_games` VALUES(78, 6, 1, 2, 3, 84, 3, 2, 226, 234, 1);
INSERT INTO `doubles_games` VALUES(79, 6, 2, 2, 3, 235, 188, 0, 193, 202, 2);
INSERT INTO `doubles_games` VALUES(80, 6, 2, 2, 3, 84, 3, 1, 226, 234, 2);
INSERT INTO `doubles_games` VALUES(85, 6, 1, 4, 5, 219, 198, 2, 37, 209, 0);
INSERT INTO `doubles_games` VALUES(86, 6, 1, 4, 5, 21, 204, 1, 35, 36, 2);
INSERT INTO `doubles_games` VALUES(87, 6, 2, 4, 5, 198, 219, 2, 37, 209, 0);
INSERT INTO `doubles_games` VALUES(88, 6, 2, 4, 5, 21, 197, 1, 35, 36, 2);
INSERT INTO `doubles_games` VALUES(89, 6, 1, 6, 7, 24, 25, 2, 65, 45, 0);
INSERT INTO `doubles_games` VALUES(90, 6, 1, 6, 7, 220, 142, 1, 183, 101, 2);
INSERT INTO `doubles_games` VALUES(91, 6, 2, 6, 7, 25, 142, 2, 65, 101, 0);
INSERT INTO `doubles_games` VALUES(92, 6, 2, 6, 7, 220, 24, 1, 183, 45, 2);
INSERT INTO `doubles_games` VALUES(93, 6, 1, 1, 8, 39, 122, 2, 14, 227, 0);
INSERT INTO `doubles_games` VALUES(94, 6, 1, 1, 8, 44, -2, 2, 221, -2, 0);
INSERT INTO `doubles_games` VALUES(95, 6, 2, 1, 8, 39, 122, 2, 221, 14, 1);
INSERT INTO `doubles_games` VALUES(96, 6, 2, 1, 8, 44, -2, 2, 227, -2, 1);
INSERT INTO `doubles_games` VALUES(97, 7, 1, 3, 8, 193, -2, 2, 227, 14, 1);
INSERT INTO `doubles_games` VALUES(98, 7, 1, 3, 8, 213, 202, 1, 221, 51, 2);
INSERT INTO `doubles_games` VALUES(99, 7, 2, 3, 8, 193, -2, 2, 227, 14, 0);
INSERT INTO `doubles_games` VALUES(100, 7, 2, 3, 8, 213, 202, 2, 221, 51, 1);
INSERT INTO `doubles_games` VALUES(101, 7, 1, 7, 5, 183, 65, 0, 35, 38, 2);
INSERT INTO `doubles_games` VALUES(102, 7, 1, 7, 5, 101, 45, 2, 37, 209, 1);
INSERT INTO `doubles_games` VALUES(103, 7, 2, 7, 5, -2, -2, 2, -2, -2, 2);
INSERT INTO `doubles_games` VALUES(104, 7, 2, 7, 5, -2, -2, 2, -2, -2, 2);
INSERT INTO `doubles_games` VALUES(105, 7, 1, 1, 2, 194, 39, 1, 3, 188, 2);
INSERT INTO `doubles_games` VALUES(106, 7, 1, 1, 2, 122, 44, 2, 84, -2, 0);
INSERT INTO `doubles_games` VALUES(107, 7, 2, 1, 2, 39, 194, 1, 3, 188, 2);
INSERT INTO `doubles_games` VALUES(108, 7, 2, 1, 2, 44, 122, 0, 84, -2, 2);
INSERT INTO `doubles_games` VALUES(113, 7, 1, 4, 6, 219, 204, 2, 142, 220, 0);
INSERT INTO `doubles_games` VALUES(114, 7, 1, 4, 6, 198, 21, 2, 24, 25, 0);
INSERT INTO `doubles_games` VALUES(115, 7, 2, 4, 6, 21, 197, 2, 142, 25, 0);
INSERT INTO `doubles_games` VALUES(116, 7, 2, 4, 6, 219, 198, 2, 24, 220, 0);
INSERT INTO `doubles_games` VALUES(125, 8, 1, 3, 7, 202, 213, 2, 183, 45, 1);
INSERT INTO `doubles_games` VALUES(126, 8, 1, 3, 7, 193, 226, 2, 65, 101, 1);
INSERT INTO `doubles_games` VALUES(127, 8, 2, 3, 7, 202, 213, 2, 183, 65, 0);
INSERT INTO `doubles_games` VALUES(128, 8, 2, 3, 7, 193, 226, 2, 45, 101, 0);
INSERT INTO `doubles_games` VALUES(133, 8, 1, 8, 5, 221, 227, 2, 37, 35, 0);
INSERT INTO `doubles_games` VALUES(134, 8, 1, 8, 5, 14, -2, 2, 209, 38, 0);
INSERT INTO `doubles_games` VALUES(135, 8, 2, 8, 5, 221, 227, 0, 35, 38, 2);
INSERT INTO `doubles_games` VALUES(136, 8, 2, 8, 5, 14, -2, 0, 37, 209, 2);
INSERT INTO `doubles_games` VALUES(137, 8, 1, 4, 1, 219, 197, 2, 194, 39, 0);
INSERT INTO `doubles_games` VALUES(138, 8, 1, 4, 1, 21, 198, 1, 44, 122, 2);
INSERT INTO `doubles_games` VALUES(139, 8, 2, 4, 1, 219, 214, 1, 194, 39, 2);
INSERT INTO `doubles_games` VALUES(140, 8, 2, 4, 1, 21, 198, 2, 44, 122, 0);
INSERT INTO `doubles_games` VALUES(141, 8, 1, 6, 2, 25, 142, 2, 3, 188, 0);
INSERT INTO `doubles_games` VALUES(142, 8, 1, 6, 2, 220, 24, 1, 84, 51, 2);
INSERT INTO `doubles_games` VALUES(143, 8, 2, 6, 2, 25, 142, 0, 3, 188, 2);
INSERT INTO `doubles_games` VALUES(144, 8, 2, 6, 2, 220, 24, 0, 84, 51, 2);
INSERT INTO `doubles_games` VALUES(145, 9, 1, 2, 4, 3, 188, 2, 219, 214, 1);
INSERT INTO `doubles_games` VALUES(146, 9, 1, 2, 4, 84, 51, 2, 197, 21, 0);
INSERT INTO `doubles_games` VALUES(147, 9, 2, 2, 4, 3, 188, 2, 219, 214, 1);
INSERT INTO `doubles_games` VALUES(148, 9, 2, 2, 4, 84, 51, 2, 197, 21, 0);
INSERT INTO `doubles_games` VALUES(149, 9, 1, 1, 6, 194, 39, 2, 24, 220, 1);
INSERT INTO `doubles_games` VALUES(150, 9, 1, 1, 6, 44, 122, 2, 142, 25, 1);
INSERT INTO `doubles_games` VALUES(151, 9, 2, 1, 6, 194, 39, 2, 24, 220, 0);
INSERT INTO `doubles_games` VALUES(152, 9, 2, 1, 6, 122, 44, 2, 142, 25, 1);
INSERT INTO `doubles_games` VALUES(153, 9, 1, 7, 8, 65, 183, 2, -2, -2, 0);
INSERT INTO `doubles_games` VALUES(154, 9, 1, 7, 8, 45, 101, 1, 14, 227, 2);
INSERT INTO `doubles_games` VALUES(155, 9, 2, 7, 8, 65, 183, 2, -2, -2, 0);
INSERT INTO `doubles_games` VALUES(156, 9, 2, 7, 8, 45, 101, 1, 14, 227, 2);
INSERT INTO `doubles_games` VALUES(157, 9, 1, 5, 3, 209, 37, 0, 213, 202, 2);
INSERT INTO `doubles_games` VALUES(158, 9, 1, 5, 3, 38, 35, 1, 193, 226, 2);
INSERT INTO `doubles_games` VALUES(159, 9, 2, 5, 3, 209, 37, 1, 213, 202, 2);
INSERT INTO `doubles_games` VALUES(160, 9, 2, 5, 3, 38, 35, 0, 193, 226, 2);
INSERT INTO `doubles_games` VALUES(161, 10, 1, 7, 4, 45, 101, 2, 21, 198, 1);
INSERT INTO `doubles_games` VALUES(162, 10, 1, 7, 4, 183, 65, 1, 219, 197, 2);
INSERT INTO `doubles_games` VALUES(163, 10, 2, 7, 4, 45, 101, 2, 21, 197, 0);
INSERT INTO `doubles_games` VALUES(164, 10, 2, 7, 4, 183, 65, 0, 219, 198, 2);
INSERT INTO `doubles_games` VALUES(165, 10, 1, 5, 6, 209, 37, 2, 142, 220, 1);
INSERT INTO `doubles_games` VALUES(166, 10, 1, 5, 6, 36, 35, 2, 24, 25, 1);
INSERT INTO `doubles_games` VALUES(167, 10, 2, 5, 6, 209, 37, 2, 142, 220, 1);
INSERT INTO `doubles_games` VALUES(168, 10, 2, 5, 6, 36, 35, 2, 24, 25, 1);
INSERT INTO `doubles_games` VALUES(169, 10, 1, 3, 1, 19, -2, 0, 122, 39, 2);
INSERT INTO `doubles_games` VALUES(170, 10, 1, 3, 1, 213, 193, 2, 194, 44, 1);
INSERT INTO `doubles_games` VALUES(171, 10, 2, 3, 1, 19, -2, 0, 122, 39, 2);
INSERT INTO `doubles_games` VALUES(172, 10, 2, 3, 1, 213, 193, 2, 194, 44, 1);
INSERT INTO `doubles_games` VALUES(173, 10, 1, 8, 2, 14, 227, 1, 3, 188, 2);
INSERT INTO `doubles_games` VALUES(174, 10, 1, 8, 2, 221, 236, 0, 84, 234, 2);
INSERT INTO `doubles_games` VALUES(175, 10, 2, 8, 2, 14, 227, 2, 3, 188, 1);
INSERT INTO `doubles_games` VALUES(176, 10, 2, 8, 2, 221, 236, 0, 84, 234, 2);
INSERT INTO `doubles_games` VALUES(177, 11, 1, 2, 5, 84, 51, 1, 209, 37, 2);
INSERT INTO `doubles_games` VALUES(178, 11, 1, 2, 5, 3, 188, 1, 38, 35, 2);
INSERT INTO `doubles_games` VALUES(179, 11, 2, 2, 5, 3, 188, 2, 209, 37, 0);
INSERT INTO `doubles_games` VALUES(180, 11, 2, 2, 5, 84, 51, 2, 35, 38, 1);
INSERT INTO `doubles_games` VALUES(181, 11, 1, 1, 7, 194, 39, 2, 101, 183, 1);
INSERT INTO `doubles_games` VALUES(182, 11, 1, 1, 7, 122, 44, 0, 65, 45, 2);
INSERT INTO `doubles_games` VALUES(183, 11, 2, 1, 7, 194, 39, 1, 183, 101, 2);
INSERT INTO `doubles_games` VALUES(184, 11, 2, 1, 7, 122, 44, 2, 65, 45, 1);
INSERT INTO `doubles_games` VALUES(185, 11, 1, 4, 3, 198, 197, 0, 213, 193, 2);
INSERT INTO `doubles_games` VALUES(186, 11, 1, 4, 3, 219, 21, 2, 202, -1, 1);
INSERT INTO `doubles_games` VALUES(187, 11, 2, 4, 3, 198, 197, 0, 213, 193, 2);
INSERT INTO `doubles_games` VALUES(188, 11, 2, 4, 3, 219, 21, 2, -1, 202, 0);
INSERT INTO `doubles_games` VALUES(189, 11, 1, 6, 8, 24, 25, 0, 14, 227, 2);
INSERT INTO `doubles_games` VALUES(190, 11, 1, 6, 8, 142, 142, 2, 221, 236, 0);
INSERT INTO `doubles_games` VALUES(191, 11, 2, 6, 8, 24, 25, 2, 14, 236, 0);
INSERT INTO `doubles_games` VALUES(192, 11, 2, 6, 8, 142, 142, 2, 227, 221, 0);
INSERT INTO `doubles_games` VALUES(193, 12, 1, 4, 8, 233, 197, 2, 236, -2, 1);
INSERT INTO `doubles_games` VALUES(194, 12, 1, 4, 8, 219, 214, 2, 221, 227, 1);
INSERT INTO `doubles_games` VALUES(195, 12, 2, 4, 8, 233, 197, 2, 236, -2, 0);
INSERT INTO `doubles_games` VALUES(196, 12, 2, 4, 8, 219, 214, 2, 221, 227, 1);
INSERT INTO `doubles_games` VALUES(197, 12, 1, 2, 7, 3, 188, 2, 65, 183, 0);
INSERT INTO `doubles_games` VALUES(198, 12, 1, 2, 7, 84, 51, 2, 101, 45, 1);
INSERT INTO `doubles_games` VALUES(199, 12, 2, 2, 7, 3, 188, 2, 65, 101, 0);
INSERT INTO `doubles_games` VALUES(200, 12, 2, 2, 7, 84, 51, 0, 183, 45, 2);
INSERT INTO `doubles_games` VALUES(201, 12, 1, 1, 5, 194, 39, 0, 35, 36, 2);
INSERT INTO `doubles_games` VALUES(202, 12, 1, 1, 5, 122, 44, 2, 37, 209, 1);
INSERT INTO `doubles_games` VALUES(203, 12, 2, 1, 5, 194, 39, 2, 36, 35, 1);
INSERT INTO `doubles_games` VALUES(204, 12, 2, 1, 5, 44, 122, 2, 37, 209, 1);
INSERT INTO `doubles_games` VALUES(205, 12, 1, 6, 3, 25, 142, 0, 193, 213, 2);
INSERT INTO `doubles_games` VALUES(206, 12, 1, 6, 3, 220, 24, 1, 202, 201, 2);
INSERT INTO `doubles_games` VALUES(207, 12, 2, 6, 3, 24, 25, 0, 193, 213, 2);
INSERT INTO `doubles_games` VALUES(208, 12, 2, 6, 3, 142, 220, 0, 19, 202, 2);
INSERT INTO `doubles_games` VALUES(209, 13, 1, 8, 1, 236, 14, 2, 194, 39, 0);
INSERT INTO `doubles_games` VALUES(210, 13, 1, 8, 1, 227, 221, 1, 44, 122, 2);
INSERT INTO `doubles_games` VALUES(211, 13, 2, 8, 1, 236, 14, 2, 194, 39, 1);
INSERT INTO `doubles_games` VALUES(212, 13, 2, 8, 1, 227, 221, 0, 122, 44, 2);
INSERT INTO `doubles_games` VALUES(213, 13, 1, 5, 4, 209, 37, 1, 198, 21, 2);
INSERT INTO `doubles_games` VALUES(214, 13, 1, 5, 4, 38, 35, 0, 214, 219, 2);
INSERT INTO `doubles_games` VALUES(215, 13, 2, 5, 4, 209, 37, 0, 197, 21, 2);
INSERT INTO `doubles_games` VALUES(216, 13, 2, 5, 4, 38, 35, 0, 214, 219, 2);
INSERT INTO `doubles_games` VALUES(217, 13, 1, 3, 2, 213, 193, 2, 3, 188, 0);
INSERT INTO `doubles_games` VALUES(218, 13, 1, 3, 2, 19, 202, 2, 84, 51, 1);
INSERT INTO `doubles_games` VALUES(219, 13, 2, 3, 2, 213, 193, 2, 3, 188, 1);
INSERT INTO `doubles_games` VALUES(220, 13, 2, 3, 2, 19, 202, 0, 84, 51, 2);
INSERT INTO `doubles_games` VALUES(221, 13, 1, 7, 6, 101, 45, 0, 142, 220, 2);
INSERT INTO `doubles_games` VALUES(222, 13, 1, 7, 6, 183, 65, 2, 24, 25, 1);
INSERT INTO `doubles_games` VALUES(223, 13, 2, 7, 6, 45, 101, 2, 142, 220, 0);
INSERT INTO `doubles_games` VALUES(224, 13, 2, 7, 6, 183, 65, 2, 24, 25, 0);
INSERT INTO `doubles_games` VALUES(225, 14, 1, 8, 3, 227, 236, 0, 193, 213, 2);
INSERT INTO `doubles_games` VALUES(226, 14, 1, 8, 3, 221, 14, 0, 19, 202, 2);
INSERT INTO `doubles_games` VALUES(227, 14, 2, 8, 3, 227, 236, 0, 213, 193, 2);
INSERT INTO `doubles_games` VALUES(228, 14, 2, 8, 3, 221, 14, 0, 19, 202, 2);
INSERT INTO `doubles_games` VALUES(229, 14, 1, 5, 7, 36, 35, 2, 183, 101, 0);
INSERT INTO `doubles_games` VALUES(230, 14, 1, 5, 7, 209, 37, 1, 65, -1, 2);
INSERT INTO `doubles_games` VALUES(231, 14, 2, 5, 7, 36, 35, 1, 101, -1, 2);
INSERT INTO `doubles_games` VALUES(232, 14, 2, 5, 7, 209, 37, 0, 183, 65, 2);
INSERT INTO `doubles_games` VALUES(233, 14, 1, 6, 4, 25, 142, 1, 197, 21, 2);
INSERT INTO `doubles_games` VALUES(234, 14, 1, 6, 4, 24, 220, 2, 204, 219, 1);
INSERT INTO `doubles_games` VALUES(235, 14, 2, 6, 4, -1, 142, 2, 198, 21, 1);
INSERT INTO `doubles_games` VALUES(236, 14, 2, 6, 4, 24, 220, 0, 204, 219, 2);
INSERT INTO `doubles_games` VALUES(237, 14, 1, 2, 1, 3, 188, 2, 194, 39, 1);
INSERT INTO `doubles_games` VALUES(238, 14, 1, 2, 1, 84, 3, 2, 44, 122, 0);
INSERT INTO `doubles_games` VALUES(239, 14, 2, 2, 1, 3, 188, 2, 194, 39, 0);
INSERT INTO `doubles_games` VALUES(240, 14, 2, 2, 1, 84, 51, 2, 44, 122, 1);
INSERT INTO `doubles_games` VALUES(253, 1, 1, 5, 8, 36, 38, 2, 236, 14, 1);
INSERT INTO `doubles_games` VALUES(254, 1, 1, 5, 8, 209, 37, 1, 221, 227, 2);
INSERT INTO `doubles_games` VALUES(255, 1, 2, 5, 8, 36, 38, 2, 236, 14, 1);
INSERT INTO `doubles_games` VALUES(256, 1, 2, 5, 8, 209, 37, 2, 221, 227, 0);
INSERT INTO `doubles_games` VALUES(257, 15, 1, 5, 6, 209, 37, 2, 142, 220, 0);
INSERT INTO `doubles_games` VALUES(258, 15, 1, 5, 6, 36, 36, 1, 24, 25, 2);
INSERT INTO `doubles_games` VALUES(259, 15, 2, 5, 6, 38, 35, 2, 24, 220, 0);
INSERT INTO `doubles_games` VALUES(260, 15, 2, 5, 6, 36, 209, 2, 142, 25, 0);
INSERT INTO `doubles_games` VALUES(261, 15, 1, 3, 1, 193, 226, 2, 194, 39, 1);
INSERT INTO `doubles_games` VALUES(262, 15, 1, 3, 1, 202, 201, 1, 122, 44, 2);
INSERT INTO `doubles_games` VALUES(263, 15, 2, 3, 1, 226, 193, 2, 194, 39, 0);
INSERT INTO `doubles_games` VALUES(264, 15, 2, 3, 1, 202, 201, 2, 122, 44, 0);
INSERT INTO `doubles_games` VALUES(265, 15, 1, 2, 4, 3, 234, 1, 21, 197, 2);
INSERT INTO `doubles_games` VALUES(266, 15, 1, 2, 4, 84, 51, 2, 204, 198, 0);
INSERT INTO `doubles_games` VALUES(267, 15, 2, 2, 4, 3, 234, 2, 21, 197, 0);
INSERT INTO `doubles_games` VALUES(268, 15, 2, 2, 4, 84, 51, 2, 204, 198, 0);
INSERT INTO `doubles_games` VALUES(269, 15, 1, 7, 8, 45, 101, 1, 14, 227, 2);
INSERT INTO `doubles_games` VALUES(270, 15, 1, 7, 8, 183, 65, 2, 236, 221, 1);
INSERT INTO `doubles_games` VALUES(271, 15, 2, 7, 8, 45, 101, 0, 14, 227, 2);
INSERT INTO `doubles_games` VALUES(272, 15, 2, 7, 8, 183, 65, 2, 236, 221, 1);
INSERT INTO `doubles_games` VALUES(273, 16, 1, 5, 8, 209, 37, 2, 227, 14, 1);
INSERT INTO `doubles_games` VALUES(274, 16, 1, 5, 8, 36, 35, 2, 236, 221, 0);
INSERT INTO `doubles_games` VALUES(275, 16, 2, 5, 8, 209, 37, 0, 14, 227, 2);
INSERT INTO `doubles_games` VALUES(276, 16, 2, 5, 8, 38, 36, 2, 236, 221, 0);
INSERT INTO `doubles_games` VALUES(277, 16, 1, 2, 1, 3, 51, 1, 194, 39, 2);
INSERT INTO `doubles_games` VALUES(278, 16, 1, 2, 1, 84, -2, 0, 122, 44, 2);
INSERT INTO `doubles_games` VALUES(279, 16, 2, 2, 1, 3, 51, 2, 194, 39, 1);
INSERT INTO `doubles_games` VALUES(280, 16, 2, 2, 1, 84, -2, 0, 122, 44, 2);
INSERT INTO `doubles_games` VALUES(281, 16, 1, 3, 4, 213, 193, 1, 219, 214, 2);
INSERT INTO `doubles_games` VALUES(282, 16, 1, 3, 4, 202, 226, 2, 204, 198, 1);
INSERT INTO `doubles_games` VALUES(283, 16, 2, 3, 4, 213, 193, 0, 219, 214, 2);
INSERT INTO `doubles_games` VALUES(284, 16, 2, 3, 4, 19, 202, 2, 204, 198, 0);
INSERT INTO `doubles_games` VALUES(285, 16, 1, 7, 6, 45, 101, 2, 24, 25, 1);
INSERT INTO `doubles_games` VALUES(286, 16, 1, 7, 6, 65, 183, 2, 142, 220, 1);
INSERT INTO `doubles_games` VALUES(287, 16, 2, 7, 6, 45, 101, 0, 142, 25, 2);
INSERT INTO `doubles_games` VALUES(288, 16, 2, 7, 6, 65, 183, 1, 24, 220, 2);
INSERT INTO `doubles_games` VALUES(289, 17, 1, 7, 5, 45, 101, 2, 37, 209, 1);
INSERT INTO `doubles_games` VALUES(290, 17, 1, 7, 5, 65, 101, 0, 38, 35, 2);
INSERT INTO `doubles_games` VALUES(291, 17, 2, 7, 5, 45, 101, 2, 37, 36, 0);
INSERT INTO `doubles_games` VALUES(292, 17, 2, 7, 5, 183, 65, 1, 38, 35, 2);
INSERT INTO `doubles_games` VALUES(293, 17, 1, 6, 8, 142, 25, 0, 227, 221, 2);
INSERT INTO `doubles_games` VALUES(294, 17, 1, 6, 8, 24, -2, 0, 14, -2, 2);
INSERT INTO `doubles_games` VALUES(295, 17, 2, 6, 8, 142, 25, 1, 227, 221, 2);
INSERT INTO `doubles_games` VALUES(296, 17, 2, 6, 8, 24, -2, 2, 14, -2, 1);
INSERT INTO `doubles_games` VALUES(297, 17, 1, 3, 2, 213, 193, 2, 51, 188, 1);
INSERT INTO `doubles_games` VALUES(298, 17, 1, 3, 2, 19, 202, 2, 84, 3, 1);
INSERT INTO `doubles_games` VALUES(299, 17, 2, 3, 2, 213, 193, 2, 51, 188, 0);
INSERT INTO `doubles_games` VALUES(300, 17, 2, 3, 2, 19, 202, 1, 84, 3, 2);
INSERT INTO `doubles_games` VALUES(301, 17, 1, 4, 1, 21, 204, 0, 122, 39, 2);
INSERT INTO `doubles_games` VALUES(302, 17, 1, 4, 1, 214, 219, 2, 44, -2, 0);
INSERT INTO `doubles_games` VALUES(303, 17, 2, 4, 1, 21, 204, 2, 122, 39, 1);
INSERT INTO `doubles_games` VALUES(304, 17, 2, 4, 1, 214, 219, 2, 44, -2, 0);
