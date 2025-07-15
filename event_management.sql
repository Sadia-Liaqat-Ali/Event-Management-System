-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 08:29 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `organizer_id` int(11) NOT NULL,
  `event_name` varchar(150) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `Attendees_No` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `organizer_id`, `event_name`, `event_date`, `event_time`, `location`, `description`, `category`, `image`, `Attendees_No`, `created_at`) VALUES
(1, 5, 'Tech Innovators Summit 2024', '2025-07-16', '21:58:00', 'Expo Center, Lahore&quot; or &quot;Zoom Meeting Link&quot;', 'Join industry leaders in a full-day business strategy workshop focusing on innovation and growth', 'workshop', 'uploads/6875a78a783c28.11199810.jpg', 1, '2025-07-15 00:57:46'),
(2, 5, 'Grand Royal Wedding Celebration', '2025-07-24', '23:00:00', 'Lahore Expo Center, Main Boulevard, Lahore', 'Join us at the luxurious Grand Sapphire Marriage Hall for an unforgettable wedding celebration. \r\n  This event is designed to showcase the finest traditions with a modern twist. Enjoy a night filled with music, gourmet cuisine, floral artistry, and heartfelt moments. \r\n  From majestic decor to live entertainment, this celebration promises to be a magical experience for both the couple and their guests.', 'festival', 'uploads/1752542179_bg.PNG', 2, '2025-07-15 01:15:46'),
(3, 3, 'Quran Class By Noman Khan', '2025-07-25', '00:14:00', 'Lahore, Pakistan ', 'Its a Emann Shaked Class about sorah E Bakra lights', 'workshop', 'uploads/1752602384_bg2.jpg', 1, '2025-07-15 16:14:56');

-- --------------------------------------------------------

--
-- Table structure for table `event_notifications`
--

CREATE TABLE `event_notifications` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `notification_title` varchar(255) NOT NULL,
  `notification_message` text DEFAULT NULL,
  `status` enum('pending','sent') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_notifications`
--

INSERT INTO `event_notifications` (`id`, `event_id`, `sender_id`, `notification_title`, `notification_message`, `status`, `created_at`) VALUES
(2, 3, 3, '🌟 You\'re Invited: Quran Class! 💍', 'kindly accept this soulful class invitation', 'sent', '2025-07-15 16:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `event_rsvps`
--

CREATE TABLE `event_rsvps` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `attendee_name` varchar(100) NOT NULL,
  `status` enum('attending','maybe','declined') DEFAULT 'attending',
  `created_at` datetime DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `event_rsvps`
--

INSERT INTO `event_rsvps` (`id`, `event_id`, `attendee_name`, `status`, `created_at`, `user_id`) VALUES
(1, 1, 'aish', 'declined', '2025-07-15 09:30:12', 6),
(2, 3, 'aish', 'maybe', '2025-07-15 09:41:46', 6),
(3, 2, 'aish', 'attending', '2025-07-15 10:06:24', 6),
(4, 2, 'sana', 'maybe', '2025-07-15 11:04:37', 9);

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('organizer','attendee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'user', 'user@gmail.com', '$2y$10$PPq4Bs/jfI.Wff5hgX/qWuwz1/vGGnzUPXDOBJqWE/8BoZHJzd366', 'organizer', '2025-07-14 22:35:10'),
(2, 'Diya', 'diya@gmail.com', '$2y$10$2FjFwocmlnYkD4Ze3g/SHulpATe6/IwHN2CojVvJsuJXbu4ogi/l.', 'attendee', '2025-07-14 22:39:18'),
(3, 'myorg', 'org@gmail.com', '$2y$10$f0kz4UfChgwzvl5e0c2YTuEkItXqxCfQDWGp4ACTTjI4V6Qg.lbWW', 'organizer', '2025-07-15 00:49:08'),
(5, 'sadia bibi', 'sadia@gmail.com', '$2y$10$tdD0UPSxsWCwLd9fcNBFFezrplUDxyvzxtT9md2//AjzE7qzWEgPa', 'organizer', '2025-07-15 00:52:16'),
(6, 'aish', 'aish@gmail.com', '$2y$10$m45FC/Q3Pbs8IKLqPG7SFeVQoo0zYt8k4VE7dEyG.p4plNh1qWZ4e', 'attendee', '2025-07-15 02:39:33'),
(9, 'sana', 'sana@gmail.com', '$2y$10$iaoYIdaxjeS6pDU0AAFyluLlsf2uJTGvlEffJ.ieW7mLjmpav3fVS', 'attendee', '2025-07-15 18:04:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizer_id` (`organizer_id`);

--
-- Indexes for table `event_notifications`
--
ALTER TABLE `event_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `event_rsvps`
--
ALTER TABLE `event_rsvps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_notifications`
--
ALTER TABLE `event_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_rsvps`
--
ALTER TABLE `event_rsvps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `register` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_notifications`
--
ALTER TABLE `event_notifications`
  ADD CONSTRAINT `event_notifications_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_notifications_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `register` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_rsvps`
--
ALTER TABLE `event_rsvps`
  ADD CONSTRAINT `event_rsvps_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_rsvps_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `register` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
