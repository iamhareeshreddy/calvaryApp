-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2019 at 05:55 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calvary_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cv_albums`
--

CREATE TABLE `cv_albums` (
  `id` int(11) NOT NULL,
  `album_name` varchar(255) DEFAULT NULL,
  `album_cover` varchar(255) NOT NULL,
  `price` float DEFAULT NULL,
  `created_at` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '1 for audio 2 for video 3 for image'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cv_albums`
--

INSERT INTO `cv_albums` (`id`, `album_name`, `album_cover`, `price`, `created_at`, `status`, `type`) VALUES
(1, 'first audio album', 'admin.jpg', 30, '2018-12-29', 1, 1),
(2, 'sec', 'admin.jpg', 2, '2018-12-29', 1, 1),
(3, 'video1 update', '1545980843beauty.jpg', 10, '2019-01-05', 1, 2),
(4, 'imahg update', '', 12, '2019-01-05', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cv_audio`
--

CREATE TABLE `cv_audio` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `audio_name` varchar(255) DEFAULT NULL,
  `path` text,
  `created_at` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cv_audio`
--

INSERT INTO `cv_audio` (`id`, `album_id`, `audio_name`, `path`, `created_at`, `status`) VALUES
(13, 2, 'test', 'uploads/albums/audio/2/bensound-extremeaction.mp3', '2019-01-05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cv_image`
--

CREATE TABLE `cv_image` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `image_name` varchar(255) DEFAULT NULL,
  `path` text,
  `created_at` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cv_login`
--

CREATE TABLE `cv_login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(1) NOT NULL,
  `forgot_string` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cv_login`
--

INSERT INTO `cv_login` (`id`, `email`, `password`, `status`, `forgot_string`) VALUES
(1, 'reddy.hareesh05@gmail.com', '9e58d0beb36cfa3edbb94a823d37044d', 1, 'KdeZySO3kR');

-- --------------------------------------------------------

--
-- Table structure for table `cv_video`
--

CREATE TABLE `cv_video` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `video_name` varchar(255) DEFAULT NULL,
  `path` text,
  `created_at` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cv_albums`
--
ALTER TABLE `cv_albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cv_audio`
--
ALTER TABLE `cv_audio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cv_image`
--
ALTER TABLE `cv_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cv_login`
--
ALTER TABLE `cv_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cv_video`
--
ALTER TABLE `cv_video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cv_albums`
--
ALTER TABLE `cv_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cv_audio`
--
ALTER TABLE `cv_audio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cv_image`
--
ALTER TABLE `cv_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cv_login`
--
ALTER TABLE `cv_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cv_video`
--
ALTER TABLE `cv_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
