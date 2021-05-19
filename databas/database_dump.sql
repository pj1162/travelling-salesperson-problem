-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 19, 2021 at 11:50 AM
-- Server version: 5.7.30
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `travelling_salesperson_problem`
--

-- --------------------------------------------------------

--
-- Table structure for table `Destination`
--

CREATE TABLE `Destination` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(8,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `journey_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `From`
--

CREATE TABLE `From` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Journey`
--

CREATE TABLE `Journey` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `To`
--

CREATE TABLE `To` (
  `id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `to_from`
--

CREATE TABLE `to_from` (
  `id` int(11) NOT NULL,
  `length` decimal(7,2) NOT NULL,
  `to_id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Destination`
--
ALTER TABLE `Destination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `destination_journey` (`journey_id`);

--
-- Indexes for table `From`
--
ALTER TABLE `From`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `destination_id` (`destination_id`);

--
-- Indexes for table `Journey`
--
ALTER TABLE `Journey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `To`
--
ALTER TABLE `To`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `destination_id` (`destination_id`);

--
-- Indexes for table `to_from`
--
ALTER TABLE `to_from`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_from_From` (`from_id`),
  ADD KEY `to_from_To` (`to_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Destination`
--
ALTER TABLE `Destination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `From`
--
ALTER TABLE `From`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Journey`
--
ALTER TABLE `Journey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `To`
--
ALTER TABLE `To`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `to_from`
--
ALTER TABLE `to_from`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Destination`
--
ALTER TABLE `Destination`
  ADD CONSTRAINT `destination_journey` FOREIGN KEY (`journey_id`) REFERENCES `Journey` (`id`);

--
-- Constraints for table `From`
--
ALTER TABLE `From`
  ADD CONSTRAINT `From_Destination` FOREIGN KEY (`destination_id`) REFERENCES `Destination` (`id`);

--
-- Constraints for table `To`
--
ALTER TABLE `To`
  ADD CONSTRAINT `To_Destination` FOREIGN KEY (`destination_id`) REFERENCES `Destination` (`id`);

--
-- Constraints for table `to_from`
--
ALTER TABLE `to_from`
  ADD CONSTRAINT `to_from_From` FOREIGN KEY (`from_id`) REFERENCES `From` (`id`),
  ADD CONSTRAINT `to_from_To` FOREIGN KEY (`to_id`) REFERENCES `To` (`id`);
