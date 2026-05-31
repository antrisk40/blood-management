-- ==========================================
-- Blood Bank Management System Database Schema
-- Designed for Associate SDE (Web) Assignment
-- ==========================================

CREATE DATABASE IF NOT EXISTS `blood_bank` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `blood_bank`;

-- Disable foreign key checks temporarily during setup
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------
-- Table structure for table `users`
-- ------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(100) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `role` enum('hospital', 'receiver') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for table `hospitals`
-- ------------------------------------------
DROP TABLE IF EXISTS `hospitals`;
CREATE TABLE `hospitals` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) UNSIGNED NOT NULL,
  `hospital_name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  CONSTRAINT `fk_hospitals_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for table `receivers`
-- ------------------------------------------
DROP TABLE IF EXISTS `receivers`;
CREATE TABLE `receivers` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `blood_group` varchar(10) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  CONSTRAINT `fk_receivers_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for table `blood_groups`
-- ------------------------------------------
DROP TABLE IF EXISTS `blood_groups`;
CREATE TABLE `blood_groups` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `group_name` varchar(10) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default blood groups
INSERT INTO `blood_groups` (`id`, `group_name`) VALUES
(1, 'O-'),
(2, 'O+'),
(3, 'A-'),
(4, 'A+'),
(5, 'B-'),
(6, 'B+'),
(7, 'AB-'),
(8, 'AB+');

-- ------------------------------------------
-- Table structure for table `blood_samples`
-- ------------------------------------------
DROP TABLE IF EXISTS `blood_samples`;
CREATE TABLE `blood_samples` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `hospital_id` int(11) UNSIGNED NOT NULL,
  `blood_group_id` int(11) UNSIGNED NOT NULL,
  `units_available` int(11) NOT NULL DEFAULT 1,
  `expiry_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  CONSTRAINT `fk_blood_samples_hospital_id` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_blood_samples_blood_group_id` FOREIGN KEY (`blood_group_id`) REFERENCES `blood_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------
-- Table structure for table `blood_requests`
-- ------------------------------------------
DROP TABLE IF EXISTS `blood_requests`;
CREATE TABLE `blood_requests` (
  `id` int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `receiver_id` int(11) UNSIGNED NOT NULL,
  `blood_sample_id` int(11) UNSIGNED NOT NULL,
  `units_requested` int(11) NOT NULL DEFAULT 1,
  `status` enum('PENDING', 'APPROVED', 'REJECTED', 'DELIVERED') DEFAULT 'PENDING',
  `requested_at` datetime DEFAULT NULL,
  UNIQUE KEY `uidx_receiver_sample` (`receiver_id`, `blood_sample_id`),
  CONSTRAINT `fk_blood_requests_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `receivers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_blood_requests_blood_sample_id` FOREIGN KEY (`blood_sample_id`) REFERENCES `blood_samples` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enable foreign key checks back
SET FOREIGN_KEY_CHECKS = 1;
