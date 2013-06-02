-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 02, 2013 at 04:06 PM
-- Server version: 5.5.30
-- PHP Version: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `amsUser`
--

-- --------------------------------------------------------

--
-- Table structure for table `activityLog`
--

DROP TABLE IF EXISTS `activityLog`;
CREATE TABLE IF NOT EXISTS `activityLog` (
  `activityId` bigint(20) NOT NULL AUTO_INCREMENT,
  `requestId` bigint(20) NOT NULL,
  `appKey` varchar(100) NOT NULL,
  `typeId` smallint(6) NOT NULL DEFAULT '0',
  `deviceId` varchar(128) NOT NULL,
  `deviceSDK` varchar(128) NOT NULL,
  `deviceModel` varchar(50) NOT NULL,
  `platform` varchar(25) NOT NULL,
  `message` varchar(5000) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `notes` varchar(5000) NOT NULL,
  `createStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`activityId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=329 ;

-- --------------------------------------------------------

--
-- Table structure for table `activityType`
--

DROP TABLE IF EXISTS `activityType`;
CREATE TABLE IF NOT EXISTS `activityType` (
  `typeId` int(11) NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

DROP TABLE IF EXISTS `application`;
CREATE TABLE IF NOT EXISTS `application` (
  `appID` int(11) NOT NULL AUTO_INCREMENT,
  `appKey` varchar(50) NOT NULL,
  `appName` varchar(100) NOT NULL,
  `createStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `obsolete` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`appID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `rawXMLRequest`
--

DROP TABLE IF EXISTS `rawXMLRequest`;
CREATE TABLE IF NOT EXISTS `rawXMLRequest` (
  `requestId` bigint(20) NOT NULL AUTO_INCREMENT,
  `rawXML` text NOT NULL,
  `createStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`requestId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=329 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
