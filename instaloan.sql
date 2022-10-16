-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2021 at 06:54 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `instaloan`
--

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `duration` int(12) NOT NULL,
  `monthly` int(100) NOT NULL,
  `completed` int(100) NOT NULL DEFAULT 0,
  `balance` int(100) NOT NULL,
  `loan_number` int(100) NOT NULL,
  `approve_by` varchar(50) NOT NULL,
  `date_approve` date NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) CHARACTER SET ucs2 COLLATE ucs2_bin NOT NULL DEFAULT 'UNPAID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan`
--

INSERT INTO `loan` (`loan_id`, `user_id`, `amount`, `duration`, `monthly`, `completed`, `balance`, `loan_number`, `approve_by`, `date_approve`, `status`) VALUES
(1, 16, 3500, 7, 515, 7, 0, 84213609, '1', '2021-06-05', 'PAID'),
(2, 17, 3000, 3, 1030, 3, 0, 15818056, '1', '2021-07-06', 'PAID'),
(3, 20, 3000, 3, 1030, 3, 0, 27193208, '1', '2021-08-06', 'PAID'),
(18, 16, 2000, 3, 687, 3, 0, 31314282, '1', '2021-09-09', 'PAID'),
(19, 17, 3000, 3, 1030, 3, 0, 92351030, '1', '2021-10-09', 'PAID'),
(20, 20, 2500, 3, 858, 3, 0, 13340592, '1', '2021-11-09', 'PAID'),
(21, 28, 5000, 7, 736, 7, 0, 56944138, '1', '2021-12-09', 'PAID'),
(22, 29, 4000, 5, 824, 5, 0, 23813261, '1', '2021-01-09', 'PAID'),
(23, 20, 3000, 3, 1030, 3, 0, 27193208, '1', '2021-02-06', 'PAID'),
(24, 17, 3000, 3, 1030, 3, 0, 92351030, '1', '2021-03-09', 'PAID'),
(25, 28, 5000, 7, 736, 7, 0, 56944138, '1', '2021-04-09', 'PAID'),
(26, 29, 4000, 5, 824, 5, 0, 23813261, '1', '2021-05-09', 'PAID'),
(27, 32, 3000, 6, 515, 6, 0, 77597769, '1', '2021-07-09', 'PAID'),
(28, 16, 5000, 5, 1030, 5, 0, 45829477, '1', '2021-07-09', 'PAID'),
(29, 34, 3000, 6, 515, 6, 0, 17089453, '1', '2021-07-10', 'PAID'),
(30, 17, 3000, 3, 1030, 3, 0, 92351030, '1', '2021-03-09', 'PAID'),
(31, 29, 4000, 5, 824, 5, 0, 23813261, '1', '2021-03-09', 'PAID'),
(32, 17, 3000, 3, 1030, 3, 0, 92351030, '1', '2021-04-09', 'PAID'),
(33, 20, 3000, 3, 1030, 3, 0, 27193208, '1', '2021-08-06', 'PAID'),
(34, 16, 5000, 5, 1030, 5, 0, 45829477, '1', '2021-08-09', 'PAID'),
(35, 32, 3000, 6, 515, 6, 0, 77597769, '1', '2021-11-09', 'PAID'),
(36, 29, 3000, 6, 515, 0, 3090, 27012347, '1', '2021-01-10', 'UNPAID'),
(37, 30, 3000, 3, 1030, 0, 3090, 26839700, '1', '2021-01-10', 'UNPAID'),
(38, 32, 5000, 7, 736, 0, 5152, 45340182, '1', '2021-03-10', 'UNPAID'),
(39, 31, 4000, 5, 824, 0, 4120, 60655599, '1', '2021-07-10', 'UNPAID');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `receiver` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `title`, `message`, `date`, `receiver`, `user_id`, `status`) VALUES
(9, 'Loan Application', 'Jhon Doe requested for a loan', '2021-07-08 08:37:05', 'Admin', 16, 'read'),
(10, 'Loan Application', 'Coco Martin requested for a loan', '2021-07-08 08:51:56', 'Admin', 20, 'read'),
(11, 'Loan Application', 'Coco Martin requested for a loan', '2021-07-08 20:44:16', 'Admin', 20, 'read'),
(12, 'Loan Application', 'Jhon Doe requested for a loan', '2021-07-08 20:44:28', 'Admin', 16, 'read'),
(13, 'Loan Aplication', 'Your loan application has been approved.', '2021-07-08 20:50:33', 'User', 20, 'read'),
(14, 'Loan Application', 'Coco Martin requested for a loan', '2021-07-08 20:57:19', 'Admin', 20, 'read'),
(15, 'Loan Aplication', 'Your loan application has been approved.', '2021-07-08 20:57:36', 'User', 20, 'read'),
(16, 'Loan Application', 'Coco Martin requested for a loan', '2021-07-08 21:11:26', 'Admin', 20, 'read'),
(18, 'Loan Application', 'Coco Martin requested for a loan', '2021-07-08 21:21:33', 'Admin', 20, 'read'),
(19, 'Loan Aplication', 'Sample Message 2/Sample Sample', '2021-07-08 21:22:03', 'User', 20, 'read'),
(20, 'Loan Application', 'Coco Martin requested for a loan', '2021-07-08 21:32:15', 'Admin', 20, 'read'),
(22, 'Loan Application', 'Coco Martin requested for a loan', '2021-07-08 21:36:52', 'Admin', 20, 'read'),
(23, 'Loan Aplication', 'Poor credit history/', '2021-07-08 21:38:38', 'User', 20, 'read'),
(24, 'Loan Application', 'Lyca Lee requested for a loan', '2021-07-09 22:06:19', 'Admin', 28, 'read'),
(25, 'Loan Application', 'Gian Cruz requested for a loan', '2021-07-09 22:07:24', 'Admin', 29, 'read'),
(26, 'Loan Application', 'Carlo Aquino requested for a loan', '2021-07-09 22:08:03', 'Admin', 30, 'read'),
(27, 'Loan Application', 'Marie Agcaoili requested for a loan', '2021-07-09 22:09:20', 'Admin', 31, 'read'),
(28, 'Loan Aplication', 'Your loan application has been approved.', '2021-07-09 22:10:09', 'User', 28, 'read'),
(29, 'Loan Aplication', 'Your loan application has been approved.', '2021-07-09 22:16:06', 'User', 29, 'read'),
(30, 'Loan Aplication', 'Don’t have regular income/', '2021-07-09 22:16:28', 'User', 30, 'read'),
(31, 'Loan Aplication', 'Don’t have regular income/Sample', '2021-07-09 22:16:40', 'User', 31, 'read'),
(32, 'Loan Application', 'Kyla Abadilla requested for a loan', '2021-07-09 22:21:29', 'Admin', 32, 'read'),
(33, 'Loan Aplication', 'Your application has been rejected./Inaccurate Details in Application/Sample', '2021-07-09 22:22:00', 'User', 32, 'read'),
(34, 'Loan Application', 'Gian Cruz requested for a loan', '2021-07-09 22:35:55', 'Admin', 29, 'read'),
(35, 'Loan Aplication', 'Your application has been rejected./Poor credit history/', '2021-07-09 22:38:35', 'User', 29, 'read'),
(36, 'Loan Application', 'Gian Cruz requested for a loan', '2021-07-09 22:40:32', 'Admin', 29, 'read'),
(37, 'Loan Aplication', 'Your application has been rejected./Reason: Don’t have regular income/Sample', '2021-07-09 22:40:55', 'User', 29, 'read'),
(38, 'Loan Application', 'Gian Cruz requested for a loan', '2021-07-09 22:41:30', 'Admin', 29, 'read'),
(39, 'Loan Aplication', 'Your application has been rejected./Reason:/Inaccurate Details in Application/Sample', '2021-07-09 22:41:48', 'User', 29, 'read'),
(40, 'Loan Application', 'Kyla Abadilla requested for a loan', '2021-07-09 22:42:38', 'Admin', 32, 'read'),
(41, 'Loan Aplication', 'Your loan application has been approved.', '2021-07-09 22:42:53', 'User', 32, 'read'),
(42, 'Loan Application', 'Jamaica Bolando requested for a loan', '2021-07-09 22:44:29', 'Admin', 34, 'read'),
(43, 'Loan Aplication', 'Your application has been rejected./Reason:/Not employed/', '2021-07-09 22:44:46', 'User', 34, 'read'),
(44, 'Loan Aplication', 'Payment Received/Your new balance is 2575', '2021-07-09 22:53:30', 'User', 32, 'read'),
(45, 'Loan Aplication', 'Payment Received/Your new balance is 2060', '2021-07-09 22:53:31', 'User', 32, 'read'),
(46, 'Loan Aplication', 'Payment Received/Your new balance is 1545', '2021-07-09 22:53:33', 'User', 32, 'read'),
(47, 'Loan Aplication', 'Payment Received/Your new balance is 1030', '2021-07-09 22:53:34', 'User', 32, 'read'),
(48, 'Loan Aplication', 'Payment Received/Your new balance is 515', '2021-07-09 22:53:36', 'User', 32, 'read'),
(49, 'Loan Aplication', 'Payment Received/Your new balance is 0', '2021-07-09 22:53:38', 'User', 32, 'read'),
(50, 'Loan Aplication', 'Your loan has been fully paid.', '2021-07-09 22:53:38', 'User', 32, 'read'),
(51, 'Loan Aplication', 'Payment Received/Your new balance is 4,120', '2021-07-09 22:57:30', 'User', 16, 'read'),
(52, 'Loan Aplication', 'Payment Received/Your new balance is 3,090', '2021-07-09 22:57:32', 'User', 16, 'read'),
(53, 'Loan Aplication', 'Payment Received/Your new balance is 2,060', '2021-07-09 22:57:34', 'User', 16, 'read'),
(54, 'Loan Aplication', 'Payment Received/Your new balance is 1,030', '2021-07-09 22:57:35', 'User', 16, 'read'),
(55, 'Loan Aplication', 'Payment Received/Your new balance is 0', '2021-07-09 22:57:38', 'User', 16, 'read'),
(56, 'Loan Aplication', 'Your loan has been fully paid.', '2021-07-09 22:57:38', 'User', 16, 'read'),
(57, 'Loan Aplication', 'Payment Received/Your new balance is 2,575', '2021-07-10 00:39:09', 'User', 34, 'read'),
(58, 'Loan Aplication', 'Payment Received/Your new balance is 2,060', '2021-07-10 00:39:12', 'User', 34, 'read'),
(59, 'Loan Aplication', 'Payment Received/Your new balance is 1,545', '2021-07-10 00:39:14', 'User', 34, 'read'),
(60, 'Loan Aplication', 'Payment Received/Your new balance is 1,030', '2021-07-10 00:39:19', 'User', 34, 'read'),
(61, 'Loan Aplication', 'Payment Received/Your new balance is 515', '2021-07-10 00:39:22', 'User', 34, 'read'),
(62, 'Loan Aplication', 'Payment Received/Your new balance is 515', '2021-07-10 00:39:22', 'User', 34, 'read'),
(63, 'Loan Aplication', 'Payment Received/Your new balance is 0', '2021-07-10 00:39:24', 'User', 34, 'read'),
(64, 'Loan Aplication', 'Your loan has been fully paid.', '2021-07-10 00:39:24', 'User', 34, 'read');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(100) NOT NULL,
  `payment_number` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `payment_date` date DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `payment_number`, `user_id`, `amount`, `payment_date`) VALUES
(1, 23708080, 16, 515, '2021-07-05'),
(2, 94181148, 16, 515, '2021-07-05'),
(3, 61262120, 16, 515, '2021-07-05'),
(4, 55281660, 16, 515, '2021-07-05'),
(5, 64535813, 16, 515, '2021-07-05'),
(6, 39231525, 16, 515, '2021-07-05'),
(7, 28341681, 16, 515, '2021-07-05'),
(8, 32740377, 17, 1030, '2021-07-06'),
(9, 29737236, 17, 1030, '2021-07-06'),
(10, 62614464, 17, 1030, '2021-07-06'),
(11, 77576313, 20, 1030, '2021-07-06'),
(12, 73755534, 20, 1030, '2021-07-06'),
(13, 85207499, 20, 1030, '2021-07-06'),
(14, 59527996, 16, 687, '2021-07-09'),
(15, 13008955, 16, 687, '2021-07-09'),
(16, 24509437, 16, 687, '2021-07-09'),
(17, 34640895, 17, 1030, '2021-07-09'),
(18, 53491541, 17, 1030, '2021-07-09'),
(19, 64103037, 17, 1030, '2021-07-09'),
(20, 72973364, 20, 858, '2021-07-09'),
(21, 82777485, 20, 858, '2021-07-09'),
(22, 82744838, 20, 858, '2021-07-09'),
(23, 95515530, 28, 736, '2021-07-09'),
(24, 22046811, 28, 736, '2021-07-09'),
(25, 76701131, 28, 736, '2021-07-09'),
(26, 10686188, 28, 736, '2021-07-09'),
(27, 78250452, 28, 736, '2021-07-09'),
(28, 12078607, 28, 736, '2021-07-09'),
(29, 80058178, 28, 736, '2021-07-09'),
(30, 19943582, 29, 824, '2021-07-09'),
(31, 30672798, 29, 824, '2021-07-09'),
(32, 61803032, 29, 824, '2021-07-09'),
(33, 76241893, 29, 824, '2021-07-09'),
(34, 39478773, 29, 824, '2021-07-09'),
(35, 65112702, 32, 515, '2021-07-09'),
(36, 85690411, 32, 515, '2021-07-09'),
(37, 32191559, 32, 515, '2021-07-09'),
(38, 94770675, 32, 515, '2021-07-09'),
(39, 29651722, 32, 515, '2021-07-09'),
(40, 37908644, 32, 515, '2021-07-09'),
(41, 67745308, 16, 1030, '2021-07-09'),
(42, 87073155, 16, 1030, '2021-07-09'),
(43, 94815878, 16, 1030, '2021-07-09'),
(44, 32258060, 16, 1030, '2021-07-09'),
(45, 60748654, 16, 1030, '2021-07-09'),
(46, 80851687, 34, 515, '2021-07-10'),
(47, 10747935, 34, 515, '2021-07-10'),
(48, 45967000, 34, 515, '2021-07-10'),
(49, 83712526, 34, 515, '2021-07-10'),
(50, 33485751, 34, 515, '2021-07-10'),
(51, 25022793, 34, 515, '2021-07-10'),
(52, 28109937, 34, 515, '2021-07-10');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `duration` int(12) NOT NULL,
  `monthly` int(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(100) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `sex` varchar(6) NOT NULL,
  `contact_number` varchar(11) NOT NULL,
  `email` varchar(30) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `acct_type` varchar(10) NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `sex`, `contact_number`, `email`, `birthdate`, `address`, `username`, `password`, `acct_type`) VALUES
(1, 'Jodelmar', 'Beltran', 'Male', '09123456789', 'jodel@gmail.com', '2021-07-19', 'cagayan', 'jodel', 'beltran', 'Admin'),
(2, 'Mae', 'Teano', 'Female', '09988776655', NULL, NULL, NULL, 'mae', 'teano', 'Admin'),
(16, 'Jhon', 'Doe', 'Male', '09278706504', 'jhon@gmail.com', '2021-07-14', 'Cagayan', 'jhon', '1234', 'User'),
(17, 'Kim', 'Chiu', 'Female', '09127867685', 'kim@gmail.com', '2021-07-15', 'cagayan', 'kim', '12345', 'User'),
(20, 'Coco', 'Martin', 'Male', '09234623214', 'coco@gmail.com', '2021-07-14', 'cagayan', 'coco', '12345', 'User'),
(28, 'Lyca', 'Lee', 'Female', '09384755661', NULL, NULL, NULL, 'lyca', '12345', 'User'),
(29, 'Gian', 'Cruz', 'Male', '09121998987', NULL, NULL, NULL, 'gian', 'cruz', 'User'),
(30, 'Carlo', 'Aquino', 'Male', '09216456738', NULL, NULL, NULL, 'carlo', 'aquino', 'User'),
(31, 'Marie', 'Agcaoili', 'Female', '09867473920', NULL, NULL, NULL, 'marie', '12345', 'User'),
(32, 'Kyla', 'Abadilla', 'Female', '09897301282', NULL, NULL, NULL, 'kyla', '12345', 'User'),
(33, 'Sample', 'Sample', 'Male', '09837274827', NULL, NULL, NULL, 'sample', 'sample', 'Admin'),
(34, 'Jamaica', 'Bolando', 'Female', '09879012846', NULL, NULL, NULL, 'jam', '12345', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
