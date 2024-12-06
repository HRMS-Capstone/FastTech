-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 03:51 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrm`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `time_in`, `time_out`, `status`) VALUES
(2, 1, '2024-11-21', '09:46:00', '17:00:00', 'Present'),
(3, 3, '2024-11-21', '10:05:00', '15:20:00', 'Present'),
(41, 2, '2024-11-21', '11:11:00', '17:11:00', 'Late'),
(42, 2, '2024-11-21', '11:11:00', '17:11:00', 'Late'),
(43, 3, '2024-11-21', '11:13:00', '17:13:00', 'Absent'),
(44, 4, '2024-11-21', '11:15:00', '17:15:00', 'Leave'),
(45, 3, '2024-11-21', '13:41:00', '01:41:00', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `job_title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `email`, `job_title`) VALUES
(1, 'Alexandra Janine', 'Torno', 'alextorno@gmail.com', 'Programmer Eme'),
(2, 'Careenmay', 'Melendrez', 'careenmelendrez@gmail.com', 'UI Designer'),
(3, 'Mika Ella', 'Castro', 'mikacastro@gmail.com', 'Technical Writer/ System Analyst'),
(4, 'Archie', 'Pereye', 'archiepereye@gmail.com', 'Project Manager'),
(17, 'Skyler', 'Lee', 'sky@gmail.com', 'Client'),
(18, 'Trisha Mae', 'Alaba', 'alaba@gmail.com', 'Client'),
(19, 'Kristine', 'Tihida', 'kristine@gmail.com', 'HR'),
(21, 'Robin', 'almarez', 'robin@gmail.com', 'client');

-- --------------------------------------------------------

--
-- Table structure for table `job_openings`
--

CREATE TABLE `job_openings` (
  `id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date_posted` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `pay_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `employee_id`, `amount`, `pay_date`) VALUES
(1, 1, 1200.00, '2024-10-01'),
(2, 2, 1500.00, '2024-10-01'),
(3, 3, 1000.00, '2024-10-01'),
(4, 4, 1100.00, '2024-10-01');

-- --------------------------------------------------------

--
-- Table structure for table `recruitment`
--

CREATE TABLE `recruitment` (
  `id` int(11) NOT NULL,
  `cfname` varchar(100) NOT NULL,
  `clname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('Applied','Interviewed','Hired','Rejected') NOT NULL,
  `date_applied` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruitment`
--

INSERT INTO `recruitment` (`id`, `cfname`, `clname`, `email`, `status`, `date_applied`) VALUES
(1, 'Trisha Mae', 'Alaba', 'alaba@gmail.com', 'Applied', '2024-12-01'),
(2, 'Trisha Mae', 'Alaba', 'alaba@gmail.com', 'Applied', '2024-12-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`, `reset_token`, `token_expiry`, `email`, `firstname`, `lastname`) VALUES
(11, 'KristineHr', '$2y$10$OT4kneO.n/6XE2FbJD5v5Ou8vYhvbVgngZp9VhWY4F3aB.wBT2BHy', '2024-11-12 02:24:41', 'admin', NULL, NULL, '', '', ''),
(20, 'Sensei', '$2y$10$.peNIEkVYAeWiAIVk6xxHeppK9J68FxK.jts/q2eNsnma.NmB9xpO', '2024-12-01 06:21:03', '', NULL, NULL, 'tornoalexandra9@gmail.com', 'Alexandra', 'Torno'),
(27, 'Alex', '$2y$10$Cw8Do6jYjfE9cTfgeLRKneKUkk1X8jbFmIEMU9INcUZ7KntIOLiCK', '2024-12-05 04:43:55', '', NULL, NULL, 'tornoalexandra9@gmail.com', 'Alexandra Janine', 'Torno'),
(28, 'Alex05', '$2y$10$BYuI7mI3Wa00fgMe6fRFMuVyEQakRTHx2KUS6ozVRueP1QQ/MDhWe', '2024-12-05 04:44:42', '', NULL, NULL, 'tornoalexandra9@gmail.com', 'Alexandra ', 'Torno');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `job_openings`
--
ALTER TABLE `job_openings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_ibfk_1` (`employee_id`);

--
-- Indexes for table `recruitment`
--
ALTER TABLE `recruitment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `job_openings`
--
ALTER TABLE `job_openings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `recruitment`
--
ALTER TABLE `recruitment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
