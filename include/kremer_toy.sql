-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 07, 2013 at 12:40 AM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kremer_toy`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_incident_report`
--

CREATE TABLE IF NOT EXISTS `ci_incident_report` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `store_num` varchar(255) NOT NULL,
  `shuttle_id` int(255) NOT NULL COMMENT '0 = NOT SET | 1 = Big Blue | 2 = LIttle Blue | 3 = Western | 4 = Weekend | 5 = Shuttle 5 | 6 = Weekend Western',
  `report` text NOT NULL,
  `ack` enum('T','F') NOT NULL DEFAULT 'F',
  `ack_user` varchar(255) NOT NULL,
  `ack_date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=107 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) DEFAULT '0',
  `user_agent` varchar(50) DEFAULT NULL,
  `last_activity` int(10) unsigned DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ci_synergy_message`
--

CREATE TABLE IF NOT EXISTS `ci_synergy_message` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(255) NOT NULL,
  `emp_number` int(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ack` enum('TRUE','FALSE') NOT NULL DEFAULT 'FALSE',
  `ack_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ack_ip_address` varchar(255) NOT NULL,
  `message` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer_list`
--

CREATE TABLE IF NOT EXISTS `customer_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cust_number` int(11) NOT NULL,
  `cust_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5854 ;

-- --------------------------------------------------------

--
-- Table structure for table `delete_users`
--

CREATE TABLE IF NOT EXISTS `delete_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `ip` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `fixed` enum('T','F') NOT NULL DEFAULT 'F',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81 ;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('local','other','special') DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `store` varchar(11) DEFAULT NULL,
  `emp` int(11) DEFAULT NULL,
  `location` varchar(1000) DEFAULT NULL,
  `line_code` varchar(1000) DEFAULT NULL,
  `part_num` varchar(1000) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `number` varchar(1000) DEFAULT NULL,
  `tracking` varchar(1000) DEFAULT NULL,
  `cost` varchar(1000) DEFAULT NULL,
  `sell` varchar(1000) DEFAULT NULL,
  `freight` varchar(1000) DEFAULT NULL,
  `notes` varchar(1000) DEFAULT NULL,
  `call_back` enum('T','F') NOT NULL DEFAULT 'F',
  `received` enum('T','F') NOT NULL DEFAULT 'F',
  `call_cust` enum('T','F') NOT NULL DEFAULT 'F',
  `sold` enum('T','F') NOT NULL DEFAULT 'F',
  `prebill` enum('T','F') NOT NULL DEFAULT 'F',
  `class` varchar(10) NOT NULL DEFAULT 'NO DATA',
  `will_call` enum('F','T') NOT NULL DEFAULT 'F',
  PRIMARY KEY (`id`),
  KEY `call_back` (`call_back`),
  KEY `received` (`received`),
  KEY `location` (`location`(333)),
  KEY `store` (`store`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000348725 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_delete`
--

CREATE TABLE IF NOT EXISTS `order_delete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('local','other','special') DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `store` varchar(11) DEFAULT NULL,
  `emp` int(11) DEFAULT NULL,
  `location` varchar(1000) DEFAULT NULL,
  `line_code` varchar(1000) DEFAULT NULL,
  `part_num` varchar(1000) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `number` varchar(1000) DEFAULT NULL,
  `tracking` varchar(1000) DEFAULT NULL,
  `cost` varchar(1000) DEFAULT NULL,
  `sell` varchar(1000) DEFAULT NULL,
  `freight` varchar(1000) DEFAULT NULL,
  `notes` varchar(1000) DEFAULT NULL,
  `call_back` enum('T','F') NOT NULL DEFAULT 'F',
  `received` enum('T','F') NOT NULL DEFAULT 'F',
  `call_cust` enum('T','F') NOT NULL DEFAULT 'F',
  `sold` enum('T','F') NOT NULL DEFAULT 'F',
  `prebill` enum('T','F') NOT NULL DEFAULT 'F',
  `reason` varchar(2000) NOT NULL,
  `delete_time` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000348611 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `multi_store` enum('T','F') CHARACTER SET utf8 NOT NULL DEFAULT 'F',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;
