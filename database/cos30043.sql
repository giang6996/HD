-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2024 at 10:10 AM
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
-- Database: `cos30043`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `customerName` varchar(100) NOT NULL,
  `customerEmail` varchar(255) NOT NULL,
  `customerPassword` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `customerName`, `customerEmail`, `customerPassword`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'password123'),
(2, 'Jane Smith', 'jane.smith@example.com', 'password123'),
(3, 'Alice Johnson', 'alice.johnson@example.com', 'password123'),
(4, 'Bob Brown', 'bob.brown@example.com', 'password123'),
(5, 'Charlie Davis', 'charlie.davis@example.com', 'password123'),
(6, 'test', 'test@example.us', 'test'),
(10, 'R123', 'R123@gmail.com', 'R123'),
(11, 'Giang', 'giangnguyencanh69@gmail.com', 'Giang');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `accountId` int(11) NOT NULL,
  `parkingSlotId` int(11) NOT NULL,
  `bookingTime` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `paymentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `accountId`, `parkingSlotId`, `bookingTime`, `duration`, `paymentId`) VALUES
(60, 10, 8, '2024-08-21 23:12:00', 1, 19);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `accountId` int(11) NOT NULL,
  `parkingSlotId` int(11) NOT NULL,
  `bookingDate` datetime DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `amount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `accountId`, `parkingSlotId`, `bookingDate`, `duration`, `amount`) VALUES
(37, 11, 7, '2024-08-16 05:55:00', 1, 3.99);

-- --------------------------------------------------------

--
-- Table structure for table `parking_slots`
--

CREATE TABLE `parking_slots` (
  `id` int(11) NOT NULL,
  `slotName` varchar(100) NOT NULL,
  `slotTypeId` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_slots`
--

INSERT INTO `parking_slots` (`id`, `slotName`, `slotTypeId`, `status`, `price`) VALUES
(6, 'Slot A', 2, 1, 3.99),
(7, 'Slot B', 1, 1, 3.99),
(8, 'Slot C', 2, 0, 3.99),
(9, 'Slot D', 1, 0, 3.99),
(10, 'Slot E', 2, 1, 3.99);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `accountId` int(11) NOT NULL,
  `paymentDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `amount`, `accountId`, `paymentDate`) VALUES
(11, 3.99, 6, '2024-08-03 20:09:40'),
(12, 19.95, 6, '2024-08-03 20:17:43'),
(13, 23.94, 6, '2024-08-03 20:19:13'),
(14, 11.97, 6, '2024-08-03 20:19:45'),
(15, 11.97, 10, '2024-08-03 20:25:08'),
(16, 19.95, 10, '2024-08-04 04:30:29'),
(17, 15.96, 11, '2024-08-04 05:29:39'),
(18, 15.96, 11, '2024-08-04 06:29:19'),
(19, 3.99, 10, '2024-08-04 07:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `paymentId` int(11) NOT NULL,
  `accountId` int(11) NOT NULL,
  `receiptDate` date NOT NULL,
  `amount` float NOT NULL,
  `startDate` date NOT NULL,
  `duration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `paymentId`, `accountId`, `receiptDate`, `amount`, `startDate`, `duration`) VALUES
(6, 11, 6, '2024-08-03', 3.99, '2024-08-14', 1),
(7, 12, 6, '2024-08-03', 19.95, '2024-08-14', 5),
(8, 13, 6, '2024-08-03', 23.94, '2024-08-07', 6),
(9, 14, 6, '2024-08-03', 11.97, '2024-08-23', 3),
(10, 15, 10, '2024-08-03', 11.97, '2024-08-12', 3),
(11, 16, 10, '2024-08-04', 19.95, '2024-08-13', 5),
(12, 17, 11, '2024-08-04', 15.96, '2024-08-14', 4),
(13, 18, 11, '2024-08-04', 15.96, '2024-08-28', 4),
(14, 19, 10, '2024-08-04', 3.99, '2024-08-21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `typeid` int(11) NOT NULL,
  `typename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`typeid`, `typename`) VALUES
(1, 'car'),
(2, 'bike');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicleTypeId` int(11) NOT NULL,
  `vehicleName` varchar(255) NOT NULL,
  `accountId` int(11) NOT NULL,
  `licensePlate` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customerEmail` (`customerEmail`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountId` (`accountId`),
  ADD KEY `parkingSlotId` (`parkingSlotId`),
  ADD KEY `fk_payment` (`paymentId`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountId` (`accountId`),
  ADD KEY `parkingSlotId` (`parkingSlotId`);

--
-- Indexes for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slotTypeId` (`slotTypeId`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accountId` (`accountId`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paymentId` (`paymentId`),
  ADD KEY `accountId` (`accountId`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`typeid`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicleTypeId` (`vehicleTypeId`),
  ADD KEY `accountId` (`accountId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `parking_slots`
--
ALTER TABLE `parking_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `typeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`parkingSlotId`) REFERENCES `parking_slots` (`id`),
  ADD CONSTRAINT `fk_payment` FOREIGN KEY (`paymentId`) REFERENCES `payments` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`parkingSlotId`) REFERENCES `parking_slots` (`id`);

--
-- Constraints for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD CONSTRAINT `parking_slots_ibfk_1` FOREIGN KEY (`slotTypeId`) REFERENCES `types` (`typeid`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`paymentId`) REFERENCES `payments` (`id`),
  ADD CONSTRAINT `receipts_ibfk_2` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`vehicleTypeId`) REFERENCES `types` (`typeid`),
  ADD CONSTRAINT `vehicles_ibfk_2` FOREIGN KEY (`accountId`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
