-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2016 at 09:35 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ultimate-cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_acl`
--

CREATE TABLE `cms_acl` (
  `id` int(10) NOT NULL,
  `controller` varchar(100) NOT NULL,
  `action` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `cms_acl`
--

INSERT INTO `cms_acl` (`id`, `controller`, `action`) VALUES
(4, 'admin_dashboard', 'index'),
(1, 'admin_session', 'index'),
(3, 'admin_session', 'login'),
(5, 'admin_session', 'logout'),
(2, 'error', 'error'),
(6, 'index', 'index');

-- --------------------------------------------------------

--
-- Table structure for table `cms_acl_to_roles`
--

CREATE TABLE `cms_acl_to_roles` (
  `id` int(10) NOT NULL,
  `acl_id` int(10) NOT NULL,
  `role_id` tinyint(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_acl_to_roles`
--

INSERT INTO `cms_acl_to_roles` (`id`, `acl_id`, `role_id`) VALUES
(2, 1, 1),
(3, 3, 1),
(4, 5, 1),
(5, 1, 3),
(6, 3, 3),
(7, 5, 3),
(9, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_roles`
--

CREATE TABLE `cms_roles` (
  `id` tinyint(1) NOT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_roles`
--

INSERT INTO `cms_roles` (`id`, `role`) VALUES
(1, 'Guest'),
(2, 'Moderator'),
(3, 'Admin'),
(4, 'Superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `cms_users`
--

CREATE TABLE `cms_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `notes` text NOT NULL,
  `role` varchar(20) NOT NULL,
  `role_id` int(11) NOT NULL,
  `ban` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cms_users`
--

INSERT INTO `cms_users` (`id`, `username`, `password`, `name`, `surname`, `phone`, `notes`, `role`, `role_id`, `ban`) VALUES
(1, 'djordjeadmin', '089c1a6c4906d9c73e272ab6e39dc842', 'Djordje', 'Stojiljković', '+381 60 528-9528', '', 'Superadmin', 4, 0),
(2, 'mdrago', '8f178b383653f304d22ae40d08b6e0cf', 'Milomir', 'Dragović', '', '', 'Superadmin', 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `short` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `short`, `name`, `status`, `priority`) VALUES
(1, 'en', 'English', 1, 1),
(2, 'sr', 'Serbian', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `theme_name` varchar(255) NOT NULL,
  `theme_description` longtext NOT NULL,
  `theme_folder` varchar(255) NOT NULL,
  `theme_status` tinyint(4) NOT NULL DEFAULT '0',
  `theme_screenshot` varchar(500) NOT NULL DEFAULT 'no-img.png',
  `lastchange` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `who_created` int(11) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `theme_name`, `theme_description`, `theme_folder`, `theme_status`, `theme_screenshot`, `lastchange`, `who_created`, `date_created`) VALUES
(1, 'Cardio - Bootstrap Theme', 'Cardio is a clean and modern looking, responsive one page website template built with Bootstrap. It has a gym related theme but it can be easily adjusted to fit well with any kind of topic. The template comes with a smooth page navigation and some subtle transition effects. The design is very clean and spacious with a fresh color theme and solid typography.', 'cardio', 1, '/themes/cardio/screenshot.png', '2016-11-17 14:13:50', 1, '2016-11-17 00:00:00'),
(2, 'Solid - Bootstrap Theme', 'Solid is a 7 pages theme ideal for web agencies and freelancers. Uses Font Awesome, Masonry Javascript, PrettyPhoto lightbox and nice hover effects thanks Codrops. Theme includes the Retina.js to work nice with retina display devices.', 'solid', 0, '/themes/solid/screenshot.png', '2016-11-17 14:13:50', 1, '2016-11-17 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_acl`
--
ALTER TABLE `cms_acl`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `controller` (`controller`,`action`);

--
-- Indexes for table `cms_acl_to_roles`
--
ALTER TABLE `cms_acl_to_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_roles`
--
ALTER TABLE `cms_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cms_users`
--
ALTER TABLE `cms_users`
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD KEY `short` (`short`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cms_acl`
--
ALTER TABLE `cms_acl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `cms_acl_to_roles`
--
ALTER TABLE `cms_acl_to_roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `cms_roles`
--
ALTER TABLE `cms_roles`
  MODIFY `id` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `cms_users`
--
ALTER TABLE `cms_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
