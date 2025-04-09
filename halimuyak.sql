-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 04:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `halimuyak`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `perfume_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `perfume_id`, `quantity`) VALUES
(30, 2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `perfume_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','In Transit','Completed') DEFAULT 'Pending',
  `ordered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `perfume_id`, `quantity`, `total_price`, `status`, `ordered_at`, `completed_at`) VALUES
(9, 2, 2, 1, 22000.00, 'Completed', '2025-04-09 18:18:42', '2025-04-09 18:27:54'),
(10, 2, 1, 1, 8000.00, 'Pending', '2025-04-09 18:23:51', '2025-04-09 18:23:51'),
(11, 2, 9, 1, 100.00, 'In Transit', '2025-04-09 18:23:51', '2025-04-09 18:27:47'),
(12, 2, 2, 1, 22000.00, 'Completed', '2025-04-09 18:52:02', '2025-04-09 18:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `perfumes`
--

CREATE TABLE `perfumes` (
  `perfume_id` int(11) NOT NULL,
  `perfume_name` varchar(100) DEFAULT NULL,
  `perfume_brand` varchar(100) DEFAULT NULL,
  `perfume_price` decimal(10,2) DEFAULT NULL,
  `perfume_stock` int(11) DEFAULT NULL,
  `perfume_scent_profile` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perfumes`
--

INSERT INTO `perfumes` (`perfume_id`, `perfume_name`, `perfume_brand`, `perfume_price`, `perfume_stock`, `perfume_scent_profile`, `created_at`, `updated_at`) VALUES
(1, 'Le Male Elixir', 'Jean Paul Gaultier', 8000.00, 98, 'Top notes are Lavender and Mint; middle notes are Vanilla and Benzoin; base notes are Honey, Tonka Bean and Tobacco.', '2025-04-09 16:04:32', '2025-04-09 18:23:51'),
(2, 'Tubéreuse Nue', 'Tom Ford', 22000.00, 98, 'Top notes are Ink, Vinyl, Black Pepper and Rum; middle notes are Ebony Wood, elemi, Apricot and Peony; base note is Olibanum.', '2025-04-09 16:04:32', '2025-04-09 18:52:02'),
(9, 'Baby', 'Johnson\'s', 100.00, 99, 'Top note amoy ni baby', '2025-04-09 16:04:32', '2025-04-09 18:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` enum('Admin','Customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `user_type`) VALUES
(1, 'Xyg Fred', 'Tejada', 'admin', '$2y$10$hITgjsfWgAZUsXjiKs6cCeE2pfNBfJuZpL3zzjtlrsC6nelbN0c6C', 'Admin'),
(2, 'Aiesa', 'Aydalla', 'a@gmail.com', '$2y$10$kMEUQoRuy5JIN2g7IfOY3eyISk9wmPXPAoHanysr7IQ4YxzALkJsi', 'Customer'),
(3, 'Naomi', 'Samasap', 'n@gmail.com', '$2y$10$FNqNrubPXyw5B4/4Ld8vRu/5EsmfBsu2C0/aRRR8ex6MjoUFo0V6a', 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `perfume_id` (`perfume_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `perfume_id` (`perfume_id`);

--
-- Indexes for table `perfumes`
--
ALTER TABLE `perfumes`
  ADD PRIMARY KEY (`perfume_id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `perfumes`
--
ALTER TABLE `perfumes`
  MODIFY `perfume_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`perfume_id`) REFERENCES `perfumes` (`perfume_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`perfume_id`) REFERENCES `perfumes` (`perfume_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
