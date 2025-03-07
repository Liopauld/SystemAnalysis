-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 10:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sadsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `collectionroutes`
--

CREATE TABLE `collectionroutes` (
  `route_id` int(11) NOT NULL,
  `assigned_truck` varchar(255) DEFAULT NULL,
  `optimized_path` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_description` text NOT NULL,
  `status` enum('available','claimed') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `issue_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `gps_location` varchar(255) DEFAULT NULL,
  `status` enum('open','in_progress','resolved') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pickuprequests`
--

CREATE TABLE `pickuprequests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `schedule_day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `waste_type` enum('biodegradable','non-biodegradable','recyclable') NOT NULL,
  `status` enum('pending','approved','completed','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `collection_date` date DEFAULT NULL,
  `collection_time` time DEFAULT NULL,
  `pickup_location` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickuprequests`
--

INSERT INTO `pickuprequests` (`request_id`, `user_id`, `schedule_day`, `waste_type`, `status`, `created_at`, `collection_date`, `collection_time`, `pickup_location`, `latitude`, `longitude`) VALUES
(1, 2, 'Tuesday', 'recyclable', 'approved', '2025-03-02 16:54:02', '2025-03-04', '22:55:00', 'Taguig City', NULL, NULL),
(2, 2, 'Wednesday', 'recyclable', 'approved', '2025-03-03 12:44:58', '2025-03-05', '12:30:00', 'Taguig City', NULL, NULL),
(3, 2, 'Monday', 'biodegradable', 'approved', '2025-03-03 14:42:35', '2025-03-03', '22:44:00', 'Technological University of the Philippines Taguig, East Service Road, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 14.50960000, 121.03460000),
(4, 2, 'Friday', 'biodegradable', 'approved', '2025-03-04 12:00:55', '2025-03-04', '20:01:00', 'Technological University of the Philippines Taguig, East Service Road, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 14.50960000, 121.03460000),
(5, 2, 'Tuesday', 'recyclable', 'approved', '2025-03-04 12:02:46', '2025-03-11', '20:06:00', 'Technological University of the Philippines Taguig, East Service Road, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 14.50960000, 121.03460000),
(6, 2, 'Saturday', 'biodegradable', 'approved', '2025-03-04 12:04:48', '2025-03-08', '20:06:00', 'Technological University of the Philippines Taguig, East Service Road, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 14.50960000, 121.03460000),
(7, 2, 'Monday', 'recyclable', 'approved', '2025-03-04 12:05:50', '2025-03-10', '08:05:00', 'Technological University of the Philippines Taguig, East Service Road, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 14.50960000, 121.03460000),
(8, 2, 'Friday', 'recyclable', 'approved', '2025-03-04 16:12:04', '2025-03-07', '12:11:00', 'C-5 South Link Expressway, Barangay 183, Zone 20, District 1, Pasay, Southern Manila District, Metro Manila, 1309, Philippines', 14.50823032, 121.02779388),
(9, 2, 'Tuesday', 'biodegradable', 'approved', '2025-03-05 06:15:03', '2025-03-11', '03:15:00', 'Western Bicutan Barangay Hall, Sampaguita Street, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1630, Philippines', 14.50960137, 121.03822231),
(10, 3, 'Sunday', 'recyclable', 'rejected', '2025-03-05 08:49:20', '2025-03-09', '17:50:00', 'Technological University of the Philippines Taguig, East Service Road, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 14.50960000, 121.03460000),
(11, 4, 'Wednesday', 'biodegradable', 'approved', '2025-03-05 08:56:50', '2025-03-12', '07:00:00', 'Technological University of the Philippines Taguig, East Service Road, Sitio Masagana, Western Bicutan, Taguig District 2, Taguig, Southern Manila District, Metro Manila, 1713, Philippines', 14.50960000, 121.03460000);

-- --------------------------------------------------------

--
-- Table structure for table `pickup_schedules`
--

CREATE TABLE `pickup_schedules` (
  `schedule_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `collection_date` date NOT NULL,
  `collection_time` time DEFAULT '07:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_schedules`
--

INSERT INTO `pickup_schedules` (`schedule_id`, `request_id`, `collection_date`, `collection_time`) VALUES
(12, 5, '2025-03-11', '20:06:00'),
(15, 4, '2025-03-04', '20:01:00'),
(16, 6, '2025-03-08', '20:06:00'),
(17, 7, '2025-03-10', '08:05:00'),
(18, 2, '2025-03-05', '12:30:00'),
(19, 8, '2025-03-07', '12:11:00'),
(20, 3, '2025-03-03', '22:44:00'),
(21, 1, '2025-03-04', '22:55:00'),
(22, 9, '2025-03-11', '03:15:00'),
(24, 11, '2025-03-12', '07:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `recyclingcenters`
--

CREATE TABLE `recyclingcenters` (
  `center_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` text NOT NULL,
  `accepted_materials` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE `rewards` (
  `reward_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `points_used` int(11) DEFAULT NULL,
  `reward_description` text NOT NULL,
  `redeemed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `preferences` text DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `role` enum('resident','admin','collector') DEFAULT 'resident'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `phone_number`, `address`, `preferences`, `points`, `role`) VALUES
(1, 'paul', 'paulrainiel01@gmail.com', '$2y$10$Pc9UyBXrUlftbEq5YoFC5uJ2mIRSa8SEG1a6GZrNVB3erv0RMV8fu', '09770035933', 'Wawa, Taguig City', NULL, 0, 'admin'),
(2, 'patrick', 'patrickrainielsyparrado@gmail.com', '$2y$10$3rMs1N8EF3J0NG35dDRb7eE948dg80Jy8Did6LMqb78qqquxsoU92', '09770035933', 'Taguig City', NULL, 0, 'resident'),
(3, 'Decibol', 'deci@gmail', '$2y$10$STOJtXHlt2mzNwI56sJ62ulbDmH6BOLpy1OW3f9rfIszw8pfU5z0.', '0937764257', 'Adeaksdhdv, jawdysc', NULL, 0, 'collector'),
(4, 'Test Accounts Edit', 'testacc@gmail.com', '$2y$10$YLIk28aY6lcgaGh1zSCjRu6ZO2HMj5qdDT/a0MgyvQsY6/M3Sx6Bu', '0912345', 'Blokvn2 3, 23ejd new update', NULL, 0, 'resident');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collectionroutes`
--
ALTER TABLE `collectionroutes`
  ADD PRIMARY KEY (`route_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`issue_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pickuprequests`
--
ALTER TABLE `pickuprequests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `pickuprequests_ibfk_2` (`user_id`);

--
-- Indexes for table `pickup_schedules`
--
ALTER TABLE `pickup_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `recyclingcenters`
--
ALTER TABLE `recyclingcenters`
  ADD PRIMARY KEY (`center_id`);

--
-- Indexes for table `rewards`
--
ALTER TABLE `rewards`
  ADD PRIMARY KEY (`reward_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collectionroutes`
--
ALTER TABLE `collectionroutes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pickuprequests`
--
ALTER TABLE `pickuprequests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pickup_schedules`
--
ALTER TABLE `pickup_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `recyclingcenters`
--
ALTER TABLE `recyclingcenters`
  MODIFY `center_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rewards`
--
ALTER TABLE `rewards`
  MODIFY `reward_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `issues`
--
ALTER TABLE `issues`
  ADD CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `pickuprequests`
--
ALTER TABLE `pickuprequests`
  ADD CONSTRAINT `pickuprequests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `pickuprequests_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pickup_schedules`
--
ALTER TABLE `pickup_schedules`
  ADD CONSTRAINT `pickup_schedules_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `pickuprequests` (`request_id`) ON DELETE CASCADE;

--
-- Constraints for table `rewards`
--
ALTER TABLE `rewards`
  ADD CONSTRAINT `rewards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
