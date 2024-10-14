-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 14, 2024 at 12:11 PM
-- Server version: 8.0.31
-- PHP Version: 8.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social_v`
--

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL,
  `timestamp` varchar(22) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='this stores likes reactions of users on posts';

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `timestamp`) VALUES
(11, 2, 16, '1728219972');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timestamp` varchar(22) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='this is for storing user posts';

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `picture`, `timestamp`) VALUES
(1, 16, 'From today\'s featured article\n\nKen \"Snakehips\" Johnson (10 September 1914 – 8 March 1941) was a swing band leader and leading figure in black British music of the 1930s and 1940s. Born in British Guiana, he was educated in Britain and travelled to New York to immerse himself in the Harlem jazz scene. He returned to Britain and established the Aristocrats (or Emperors) of Jazz, a mainly black swing band, with Leslie Thompson. In 1937 Johnson took control of the band through a legal loophole, causing the departure of Thompson and several musicians. Johnson filled the vacancies with Caribbean musicians, the band\'s popularity grew, and it changed its name to the West Indian Dance Orchestra. In 1938 the band broadcast on BBC Radio, recorded their first discs and appeared in an early television broadcast. Johnson was considered a pioneer for black musical leaders in the UK. While the band was employed as the house band at the Café de Paris, a German bombing raid in 1941 hit the nightclub, killing Johnson.', NULL, '1725989408'),
(2, 16, 'Computer\r\n\r\nA computer is a machine that can be programmed to automatically carry out sequences of arithmetic or logical operations (computation). Modern digital electronic computers can perform generic sets of operations known as programs. These programs enable computers to perform a wide range of tasks. The term computer system may refer to a nominally complete computer that includes the hardware, operating system, software, and peripheral equipment needed and used for full operation; or to a group of computers that are linked and function together, such as a computer network or computer cluster.\r\n\r\nA broad range of industrial and consumer products use computers as control systems, including simple special-purpose devices like microwave ovens and remote controls, and factory devices like industrial robots. Computers are at the core of general-purpose devices such as personal computers and mobile devices such as smartphones. Computers power the Internet, which links billions of computers and users.', NULL, '1726496638'),
(3, 0, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries,', NULL, '1728903596');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timestamp` varchar(22) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(22) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_link` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` varchar(22) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_year` varchar(22) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(22) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interest` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='this is for storing users data';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `picture`, `timestamp`, `phone`, `address`, `website`, `social_link`, `birth_date`, `birth_year`, `gender`, `interest`, `language`) VALUES
(16, 'Abdullahi', 'Adeleke', 'abdullah@gmail.com', '$2y$10$onSN1YC2LGCoqOB9qwfk/OUjmkCZm9N25kWAfzDk7aIwbW29Y/i5i', '16_1728903671.jpeg', '1725637439', '08033333333', '5 ire akari street ilo awela road Ota ogun state', 'bnlag.com', '---', '12 April', '1992', 'male', 'coding', 'Yoruba, Hausa');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
