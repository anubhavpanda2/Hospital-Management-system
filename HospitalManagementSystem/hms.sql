-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2015 at 10:57 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `diagnosis`
--

CREATE TABLE IF NOT EXISTS `diagnosis` (
  `patient_id` varchar(5) NOT NULL,
  `doctor_id` varchar(5) NOT NULL,
  `date` date NOT NULL,
  `diagnosis` varchar(1023) NOT NULL,
  PRIMARY KEY (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `diagnosis`
--

INSERT INTO `diagnosis` (`patient_id`, `doctor_id`, `date`, `diagnosis`) VALUES
('00001', '00001', '2015-04-07', 'huehue:P');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE IF NOT EXISTS `doctors` (
  `doctor_id` varchar(5) NOT NULL,
  `name` varchar(63) NOT NULL,
  `address` varchar(255) NOT NULL,
  `ph_no` varchar(15) NOT NULL,
  `dept_id` varchar(2) NOT NULL,
  PRIMARY KEY (`doctor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `name`, `address`, `ph_no`, `dept_id`) VALUES
('00001', 'Anubhav Panda1', 'Nit Rkl1', '9438733100', '04');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `patient_id` varchar(5) NOT NULL,
  `name` varchar(63) NOT NULL,
  `address` varchar(255) NOT NULL,
  `ph_no` varchar(15) NOT NULL,
  `dob` date NOT NULL,
  `sex` varchar(1) NOT NULL,
  PRIMARY KEY (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `name`, `address`, `ph_no`, `dob`, `sex`) VALUES
('00001', 'Anubhav Panda', 'Nit Rkl', '2147483647', '2015-03-02', 'm');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `patient_id` varchar(5) NOT NULL,
  `nurse_id` varchar(5) NOT NULL,
  `date` date NOT NULL,
  `report` varchar(1023) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`patient_id`, `nurse_id`, `date`, `report`) VALUES
('00001', '00001', '2015-04-07', 'banda katuchi');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
