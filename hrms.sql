-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 09:12 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hrms`
--

-- --------------------------------------------------------

--
-- Table structure for table `healthcare_logs`
--

CREATE TABLE `healthcare_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `changed_fields` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `healthcare_logs`
--

INSERT INTO `healthcare_logs` (`id`, `user_id`, `record_id`, `changed_fields`, `updated_at`) VALUES
(1, 13, 1, 'Age changed from \"12\" to \"15\"; Age changed from \"12\" to \"15\"; ', '2025-03-04 08:29:28'),
(2, 13, 1, 'Patient name changed from \"asfd\" to \"asfd123\"; Age changed from \"15\" to \"20\"; Gender changed from \"Male\" to \"Female\"; Phone changed from \"\" to \"34543534534\"; ', '2025-03-04 08:31:07'),
(3, 13, 1, 'Current Medications changed from \"asdf\" to \"asdfasfdasdf\"; Attachment changed from \"asfd\" to \"asfdsgdfgdfsdgf\"; ', '2025-03-04 08:41:28'),
(4, 10, 11, 'Patient name changed from \"test\" to \"test1\"; ', '2025-03-04 16:19:52'),
(5, 10, 10, 'Age changed from \"23\" to \"45\"; Age changed from \"23\" to \"45\"; ', '2025-03-04 16:20:06'),
(6, 10, 10, 'Age changed from \"45\" to \"46\"; ', '2025-03-04 16:20:39'),
(7, 10, 11, 'Patient name changed from \"test1\" to \"test1234\"; Allergies changed from \"asf\" to \"asfADFSS\"; Current Medications changed from \"asdf\" to \"asdfSDF\"; ', '2025-03-05 06:14:40'),
(8, 10, 14, 'Attachment changed from \"\" to \"uploads/1741156416_Screenshot 2023-05-11 102941.png\"; ', '2025-03-05 06:33:36'),
(9, 10, 14, 'Current Medications changed from \"paracetamol\" to \"paracetamol1\"; ', '2025-03-05 06:49:27'),
(10, 10, 14, 'Current Medications changed from \"paracetamol1\" to \"paracetamol12\"; ', '2025-03-05 06:50:27'),
(11, 10, 14, 'Attachment changed from \"uploads/1741156416_Screenshot 2023-05-11 102941.png\" to \"uploads/1741157435_Screenshot 2023-04-25 202736.png\"; ', '2025-03-05 06:50:35'),
(12, 10, 14, 'Age changed from \"34\" to \"33\"; ', '2025-03-05 06:51:13'),
(13, 10, 14, 'Current Medications changed from \"paracetamol12\" to \"paracetamol, zifi 200\"; ', '2025-03-05 11:23:06'),
(14, 10, 11, 'Current Medications changed from \"asdfSDF\" to \"None\"; ', '2025-03-05 12:27:21'),
(15, 17, 19, 'Allergies changed from \"asf\" to \"asf32\"; Attachment changed from \"\" to \"uploads/1741189274_netmeds payment.png\"; ', '2025-03-05 15:41:14'),
(16, 17, 22, 'Attachment changed from \"uploads/1741197154_azure note.txt\" to \"uploads/1741197169_Chandresh Suvagiya_SAP BASIS and HANA Consultant.pdf\"; ', '2025-03-05 17:52:49'),
(17, 17, 22, 'Attachment changed from \"uploads/1741197169_Chandresh Suvagiya_SAP BASIS and HANA Consultant.pdf\" to \"uploads/1741197178_advance php.docx\"; ', '2025-03-05 17:52:58'),
(18, 17, 22, 'Attachment changed from \"uploads/1741197178_advance php.docx\" to \"uploads/1741197191_test.html\"; ', '2025-03-05 17:53:11'),
(19, 10, 25, 'Allergies changed from \"fsd\" to \"fsdasdfasdf\"; ', '2025-03-06 10:51:12'),
(20, 17, 22, 'Age changed from \"0\" to \"12\"; ', '2025-03-06 11:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `healthcare_records`
--

CREATE TABLE `healthcare_records` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `allergies` text NOT NULL,
  `medications` text NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `healthcare_records`
--

INSERT INTO `healthcare_records` (`id`, `patient_name`, `age`, `gender`, `allergies`, `medications`, `attachment`, `created_at`, `created_by`) VALUES
(1, 'asfd123', 20, 'Female', '34543534534', 'asdfasfdasdf', 'asfdsgdfgdfsdgf', '2025-03-04 08:29:18', 13),
(7, 'Ruta', 33, 'Male', 'test', '', 'uploads/1741094940_Screenshot 2025-02-22 122904.png', '2025-03-04 13:30:04', 13),
(8, 'test', 23, 'Male', 'asf', 'asdf', 'uploads/1741095537_SpouseAdd_Error.png', '2025-03-04 13:38:57', 10),
(9, 'test', 23, 'Male', 'asf', 'asdf', 'uploads/1741095572_SpouseAdd_Error.png', '2025-03-04 13:39:32', 10),
(10, 'test', 46, 'Male', 'asf', 'asdf', 'uploads/1741095582_SpouseAdd_Error.png', '2025-03-04 13:39:42', 10),
(11, 'test1234', 23, 'Male', 'asfADFSS', 'None', 'uploads/1741100011_SpouseAdd_Error.png', '2025-03-04 14:53:31', 10),
(14, 'Ruta', 33, 'Female', 'having high fever and cough', 'paracetamol, zifi 200', 'uploads/1741157435_Screenshot 2023-04-25 202736.png', '2025-03-05 06:27:26', 10),
(19, 'test', 32, 'Male', 'asf32', 'asdf', 'uploads/1741189274_netmeds payment.png', '2025-03-05 15:40:21', 17),
(22, 'fsad', 12, 'Other', 'asf', '', 'uploads/1741197191_test.html', '2025-03-05 17:52:34', 17),
(23, 'dsf', 0, 'Male', 'asdf', '', '', '2025-03-05 19:01:34', 10),
(24, 'sfd', 354, 'Male', 'asfd', '', 'uploads/1741201357_invoice.pdf', '2025-03-05 19:02:37', 10),
(25, 'fda', 23, 'Male', 'fsdasdfasdf', '', '', '2025-03-06 10:51:05', 10),
(26, 'test', 123, 'Male', 'asdf', 'asdf', '', '2025-03-06 11:16:27', 10),
(27, 'asdf', 23, 'Male', 'aasdf', '', '', '2025-03-07 14:11:01', 10);

--
-- Triggers `healthcare_records`
--
DELIMITER $$
CREATE TRIGGER `healthcare_log_data` AFTER UPDATE ON `healthcare_records` FOR EACH ROW BEGIN
    DECLARE changes TEXT DEFAULT '';

    -- Compare each field and log changes
    IF OLD.patient_name <> NEW.patient_name THEN
        SET changes = CONCAT(changes, 'Patient name changed from "', OLD.patient_name, '" to "', NEW.patient_name, '"; ');
    END IF;

    IF OLD.age <> NEW.age THEN
        SET changes = CONCAT(changes, 'Age changed from "', OLD.age, '" to "', NEW.age, '"; ');
    END IF;
	
	IF OLD.gender <> NEW.gender THEN
        SET changes = CONCAT(changes, 'Gender changed from "', OLD.gender, '" to "', NEW.gender, '"; ');
    END IF;
	
	
	IF OLD.allergies <> NEW.allergies THEN
        SET changes = CONCAT(changes, 'Allergies changed from "', OLD.allergies, '" to "', NEW.allergies, '"; ');
    END IF;
	
	IF OLD.medications <> NEW.medications THEN
        SET changes = CONCAT(changes, 'Current Medications changed from "', OLD.medications, '" to "', NEW.medications, '"; ');
    END IF;
    
    IF OLD.attachment <> NEW.attachment THEN
        SET changes = CONCAT(changes, 'Attachment changed from "', OLD.attachment, '" to "', NEW.attachment, '"; ');
    END IF;

    IF changes <> '' THEN
        INSERT INTO healthcare_logs (user_id, record_id, changed_fields)
        VALUES (NEW.created_by, NEW.id, changes);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` float(10,2) NOT NULL,
  `validity` int(11) NOT NULL COMMENT 'in days'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `price`, `validity`) VALUES
(1, 'Free', 0.00, 10),
(2, 'Monthly', 1000.00, 30),
(5, 'free', 0.00, 0),
(6, 'test', 23.00, 32),
(7, 'Premium Package', 100.00, 10),
(8, 'Premium Package', 100.00, 10),
(9, 'Premium Package', 100.00, 10),
(10, 'Premium Package', 100.00, 10),
(11, 'Premium Package', 100.00, 10),
(12, 'Premium Package', 100.00, 10),
(13, 'Premium Package', 100.00, 10),
(14, 'Premium Package', 100.00, 10),
(15, 'Premium Package', 100.00, 10),
(16, 'Premium Package', 100.00, 10),
(17, 'Premium Package', 100.00, 10),
(18, 'Premium Package', 100.00, 10),
(19, 'Premium Package', 100.00, 10),
(20, 'Premium Package', 100.00, 10),
(21, 'Premium Package', 100.00, 10),
(22, 'Premium Package', 100.00, 10),
(23, 'Premium Package', 100.00, 10),
(24, 'Premium Package', 100.00, 10),
(25, 'Premium Package', 100.00, 10),
(26, 'Premium Package', 100.00, 10),
(27, 'Premium Package', 100.00, 10),
(28, 'Premium Package', 100.00, 10),
(29, 'Premium Package', 100.00, 10),
(30, 'Premium Package', 100.00, 10),
(31, 'Premium Package', 100.00, 10),
(32, 'Premium Package', 100.00, 10),
(33, 'Premium Package', 100.00, 10),
(34, 'Premium Package', 100.00, 10),
(35, 'Premium Package', 100.00, 10),
(36, 'Premium Package', 100.00, 10),
(37, 'Premium Package', 100.00, 10),
(38, 'Premium Package', 100.00, 10),
(39, 'Premium Package', 100.00, 10),
(40, 'Premium Package', 100.00, 10),
(41, 'Premium Package', 100.00, 10),
(42, 'Premium Package', 100.00, 10),
(43, 'Premium Package', 100.00, 10),
(44, 'Premium Package', 100.00, 10),
(45, 'Premium Package', 100.00, 10),
(46, 'Premium Package', 100.00, 10);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('patient','admin') NOT NULL DEFAULT 'patient'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
(10, 'test', '$2y$10$j2DutGrmUGAA.tT/Lu0dGuZa25D48o7E6ZrMGn4fNSwQg5LKm4BkK', 'test@test.com', 'patient'),
(11, 'dsf', '$2y$10$2t517R5ozEzrvZkqo9rMGuwq4/JuQ0AUYrwrETUwYgy.RTtFtK852', 'asfd@asfd.asfd', 'patient'),
(12, 'dzf', '$2y$10$udLh7vts.YSyliY9TSRcpe6cqAhtoCxMYKNttPLLRe1ZZcIZnMuSK', 'asdf@asfd.asdf', 'patient'),
(13, 'asfd', '$2y$10$DaATuT/ebv8G.7bw3rLRXevKHhrVL.siVSDkV.BgqRogbaYUJ2AIW', 'asdf@asf.asf', 'patient'),
(16, 'admin', '$2y$10$abGXm4bEbmdcbxsdWWb/V.gct6zsvgXmS9qkz8TZXHrFOQAlhA0cm', 'admin@test.com', 'admin'),
(17, 'hey', '$2y$10$/pgMWj0WUtD1B0A7cww0M.mQNqBWuaZb3eQZAEHvzvbYkeP1UjF.S', 'hey@hey.com', 'patient'),
(18, 'aaa', '$2y$10$qDEftPgRvGwLoJ4.NPSm8ehjoaC2wIPhhbpRBmXrckjt25FXTFaVS', 'a@a.com', 'patient'),
(19, 'abc', '$2y$10$sItwcbkn9bi9DIv3J75c1ufUThH5x6sKKX5VZK9F0LZQdB.xP3Qem', 'abc@asfd.asf', 'patient'),
(20, 'eee', '$2y$10$GNYSeBff2TnqS2lwiyF1muxmKbJ4ZOKabpLsbwkOK5OFV4S3.Z2GW', 'ee@asf.adsf', 'patient'),
(21, 'sfd', '$2y$10$2p1tnCpVReytoxVPQhGaPuVwvDYB.wGHMPiJbvX8coQhZ/qjxcVuO', 'admin@test.com', 'patient');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `user_default_package` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE changes TEXT DEFAULT '';

    IF  NEW.role = 'patient' THEN
		INSERT INTO user_package (user_id, package_id, end_date, price)
        VALUES (NEW.id, 1, DATE_ADD(now(), INTERVAL 10 DAY), 0);
    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_package`
--

CREATE TABLE `user_package` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL DEFAULT current_timestamp(),
  `end_date` date NOT NULL,
  `price` float(10,2) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user_package`
--

INSERT INTO `user_package` (`id`, `user_id`, `package_id`, `start_date`, `end_date`, `price`, `status`) VALUES
(1, 17, 2, '2025-03-06 12:19:53', '2025-04-05', 0.00, '1'),
(2, 18, 2, '2025-03-05 00:00:00', '2025-04-04', 0.00, '1'),
(4, 10, 5, '2025-03-06 12:00:00', '2025-03-06', 0.00, '1'),
(5, 19, 1, '2025-03-06 16:52:39', '2025-03-16', 0.00, '1'),
(6, 20, 1, '2025-03-07 18:59:54', '2025-03-17', 0.00, '1'),
(7, 21, 1, '2025-03-07 19:04:52', '2025-03-17', 0.00, '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `healthcare_logs`
--
ALTER TABLE `healthcare_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_createdBy` (`user_id`) USING BTREE;

--
-- Indexes for table `healthcare_records`
--
ALTER TABLE `healthcare_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_createdBy` (`created_by`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_package`
--
ALTER TABLE `user_package`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `healthcare_logs`
--
ALTER TABLE `healthcare_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `healthcare_records`
--
ALTER TABLE `healthcare_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_package`
--
ALTER TABLE `user_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `healthcare_records`
--
ALTER TABLE `healthcare_records`
  ADD CONSTRAINT `FK_createdBy` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
