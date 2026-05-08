-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 08, 2026 at 04:50 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `status` enum('available','borrowed') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `status`) VALUES
(1, 'HTML5 Guide', 'John Doe', 'Web', 'available'),
(2, 'Mastering CSS', 'Jane Smith', 'Design', 'available'),
(3, 'PHP for Beginners', 'Mark Ott', 'Backend', 'available'),
(4, 'MySQL Secrets', 'Sara Lee', 'Database', 'available'),
(5, 'JS Interactive', 'Tom Cook', 'JS', 'available'),
(6, 'Cyber Security', 'Alan Turing', 'Security', 'borrowed'),
(7, 'AI Basics', 'Ian Goodfellow', 'AI', 'available'),
(8, 'Cloud Computing', 'Bill Gates', 'Tech', 'available'),
(9, 'Data Science', 'Pythonist', 'Data', 'available'),
(10, 'Digital Ethics', 'Phil S.', 'Philosophy', 'available'),
(11, 'A History of Saudi Arabia', 'Madawi Al-Rasheed', 'History / Biography / Political History', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `fine_amount` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `book_id`, `borrow_date`, `return_date`, `fine_amount`) VALUES
(1, 2, 2, '2026-05-08', '2026-05-08', 0.00),
(2, 2, 2, '2026-05-08', '2026-05-08', 0.00),
(3, 2, 6, '2026-05-08', '2026-05-08', 0.00),
(4, 9, 6, '2026-05-08', NULL, 0.00),
(5, 9, 9, '2026-05-08', '2026-05-08', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','student') DEFAULT 'student'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(2, 'aryam ', 'aryamjel@gmail.com', '$2y$10$PVh7WLTyxia5Dcr5sfseq.cmTdrWb9IeS7cmjzjzNZpb.SPpTBJlS', 'student'),
(3, 'aryam ', 'aryamj@gmail.com', '$2y$10$qwMSwRJH.eGLQVoF/jXGZuKZSSXDNKOglfAC9Rd6xkcmLr.BlVFG2', 'student'),
(4, 'Aryam jeli', 'aryam@yic.edu.sa', '$2y$10$k4gqN4Nkp1CLJjs0EaIiluBhfKnNPRu9iFdUgeDB6aQRFABFQW7Z.', 'student'),
(7, 'Aryam Admin', 'admin@yic.edu.sa', '$2y$10$X8U1lb3HJiCuoukZxLXgNOjmVtBVNzyabpKAGbRJHq2FJfrYnSOI2', 'admin'),
(9, 'aryam', 'aryamjeli88@gmail.com', '$2y$10$LSDIp3qiLz5SO.DlE4m.5uXYYWeA9cv6m4QkyL6tLiGaVdXRkW2Mq', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
