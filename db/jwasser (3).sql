-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 23, 2014 at 04:06 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jwasser`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `post_id`, `user_id`, `comment`, `user_name`, `email`, `add_date`) VALUES
(1, 5, 7, 'test comment', '', '', '2014-12-25 08:10:09'),
(2, 4, 7, 'testing', '', '', '2014-12-18 15:51:09'),
(3, 4, 7, 'hi', '', '', '2014-12-19 09:18:58'),
(4, 4, 7, 'arun testing', '', '', '2014-12-19 09:46:54'),
(5, 4, 7, 'arun testing', '', '', '2014-12-19 09:47:44'),
(6, 4, 4, 'arun comment', '', '', '2014-12-19 09:52:04'),
(7, 5, 4, 'shailendra comment', '', '', '2014-12-19 09:54:00'),
(8, 4, 0, 'this is very usefull comment', 'shailendra kumar pathak', 'shallu.47@gmail.com', '2014-12-22 08:21:57'),
(9, 4, 4, 'hi ho', '', '', '2014-12-23 12:38:59'),
(10, 4, 4, 'jai mata ki', '', '', '2014-12-23 12:40:07'),
(11, 4, 4, 'ggggggggg', '', '', '2014-12-23 12:41:15');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_like` int(11) NOT NULL,
  `total_view` int(11) NOT NULL,
  `total_comment` int(11) NOT NULL,
  `post_status` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `title`, `tag`, `description`, `image`, `user_id`, `total_like`, `total_view`, `total_comment`, `post_status`, `add_date`) VALUES
(4, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 1, 24, 9, 0, '2014-12-23 12:41:15'),
(5, 'shailendra', 'shailendra', 'shailendra', 'post/user_slider1.jpg', 4, 0, 11, 0, 0, '2014-12-19 09:54:00'),
(6, 'test', 'test', 'test', 'post/user_bannerbg.png', 7, 0, 8, 0, 0, '2014-12-16 15:01:15'),
(7, 'Home', 'In The News', 'test', 'post/bannerbg.png', 7, 0, 9, 0, 0, '2014-12-16 15:08:59'),
(8, 'Home', 'In The News', 'test', 'post/bannerbg.png', 7, 0, 7, 0, 0, '2014-12-16 15:09:45'),
(10, 'Home', 'In The News', 'test', 'post/bannerbg.png', 7, 0, 6, 0, 0, '2014-12-16 15:09:45'),
(11, 'test', 'test', 'test', 'post/user_bannerbg.png', 7, 0, 5, 0, 0, '2014-12-16 15:01:15'),
(12, 'test', 'test', 'test', 'post/user_bannerbg.png', 7, 0, 5, 0, 0, '2014-12-16 15:01:15'),
(13, 'test', 'test', 'test', 'post/user_bannerbg.png', 7, 0, 3, 0, 0, '2014-12-16 15:01:15'),
(14, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 12, 0, 0, '2014-12-19 09:52:04'),
(15, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 13, 0, 0, '2014-12-19 09:52:04'),
(16, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 14, 0, 0, '2014-12-19 09:52:04'),
(17, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 15, 0, 0, '2014-12-19 09:52:04'),
(18, 'shailendra', 'shailendra', 'shailendra', 'post/user_slider1.jpg', 4, 0, 16, 0, 0, '2014-12-19 09:54:00'),
(19, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 18, 0, 0, '2014-12-19 09:52:04'),
(20, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 18, 0, 0, '2014-12-19 09:52:04'),
(21, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 19, 0, 0, '2014-12-19 09:52:04'),
(22, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 20, 0, 0, '2014-12-19 09:52:04'),
(23, 'test', 'test', 'test', 'post/user_bannerbg.png', 7, 0, 2, 0, 0, '2014-12-16 15:01:15'),
(24, 'Home', 'In The News', 'Unique Utah Homes is the best Homes for Sale real estate Company to help you to purchase and sell your house in Provo, USA.', 'post/user_property1.jpg', 4, 0, 21, 0, 0, '2014-12-19 09:52:04'),
(25, 'dfdfdfdsf', 'fdsfdsfds', 'fdsfdsfsdf', 'post/mantis_logo.png', 0, 0, 5, 0, 0, '2014-12-19 14:44:56'),
(27, 'Home', 'In The News', 'fdfdsfdsf', 'post/post_Koala.jpg', 4, 0, 10, 0, 1, '2014-12-23 11:39:26'),
(28, 'Fdsfds', 'dsfdsfdsf', 'dsfdsfdsfdsf', 'post/post_Desert.jpg', 4, 1, 26, 0, 0, '2014-12-23 11:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `post_like`
--

CREATE TABLE IF NOT EXISTS `post_like` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `post_like`
--

INSERT INTO `post_like` (`post_id`, `user_id`) VALUES
(5, 7),
(4, 7),
(28, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` varchar(255) NOT NULL,
  `reset_key` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `post_status` int(11) NOT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `name`, `images`, `reset_key`, `status`, `post_status`, `add_date`) VALUES
(1, 'shailendra', '35eceaa876a167ac8edc2ced0be72eb4', '', '', '', '', 0, 0, '0000-00-00 00:00:00'),
(2, 'cust4296', '21232f297a57a5a743894a0e4a801fc3', 'shallu.47@gmail.com', 'shailendra', 'user/user_property1.jpg', '', 0, 0, '2014-12-15 12:20:58'),
(3, 'ajay', 'e10adc3949ba59abbe56e057f20f883e', 'shailendra.pathak47@gmail.com', 'ajay kumar pathak', 'user/user_property3.jpg', '', 0, 0, '2014-12-15 12:25:15'),
(4, 'arun', '722279e9e630b3e731464b69968ea4b4', 'arun@gmail.com', 'arun', 'user/user_Tulips.jpg', '', 1, 0, '2014-12-15 12:37:43'),
(7, 'rohit', '2d235ace000a3ad85f590e321c89bb99', 'rohit@gmail.com', 'rohit', 'user/user_slider1.jpg', '', 1, 0, '2014-12-15 13:12:54'),
(8, 'Ajay kumar', '2cfd0dfd97b1c50548f6f7ae276ff53b', 'ajay@gmail.com', 'ajay kumar pathak', 'user/user_close.png', '', 1, 0, '2014-12-19 14:52:10');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
