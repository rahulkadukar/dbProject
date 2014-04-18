-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 06, 2013 at 08:37 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `a1560963_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `steam_hide_appid`
--

CREATE TABLE IF NOT EXISTS `steam_hide_appid` (
  `AppID` int(11) NOT NULL,
  `ReasonCode` varchar(20) NOT NULL,
  `MainGameID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `steam_hide_appid`
--

INSERT INTO `steam_hide_appid` (`AppID`, `ReasonCode`, `MainGameID`) VALUES
(65920, 'DLC', 8980),
(40940, 'DLC', 8980),
(50110, 'DLC', 8980),
(8990, 'DLC', 8980),
(260, 'Beta', 240),
(21110, 'DLC', 21090),
(21120, 'DLC', 21090),
(21960, 'DLC', 19900),
(12250, 'Apple', 12120),
(12240, 'Apple', 12110),
(12230, 'Apple', 12100),
(35420, 'GameMod', 1250),
(216370, 'Beta', 96800),
(520, 'Beta', 440),
(2430, 'Tutorial', 2420),
(100, 'Deleted', 80),
(23440, 'DLC', 23420),
(22470, 'DLC', 22380),
(72730, 'DLC', 22380),
(72740, 'DLC', 22380),
(73035, 'DLC', 42910),
(73037, 'DLC', 42910),
(73031, 'DLC', 42910),
(73054, 'DLC', 42910),
(73036, 'DLC', 42910),
(42918, 'DLC', 42910),
(73030, 'DLC', 42910),
(225760, 'Deleted', 96800),
(207815, 'DLC', 24240),
(34440, 'Apple', 3900),
(34460, 'Apple', 8800),
(34470, 'Apple', 16810),
(34450, 'Apple', 3990),
(42710, 'MultiPlayer', 42700),
(42690, 'MultiPlayer', 42680),
(10190, 'MultiPlayer', 10180),
(18508, 'DLC', 18500),
(18509, 'DLC', 18500),
(18520, 'DLC', 18500),
(18521, 'DLC', 18500);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
