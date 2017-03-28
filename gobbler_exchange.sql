-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2017 at 08:13 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gobbler_exchange`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`id`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `postId` int(11) NOT NULL,
  `notesId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `userId`, `timestamp`, `comment`, `postId`, `notesId`) VALUES
(13, 1, '2017-03-27 00:00:00', 'comment on notes', 0, 3),
(14, 1, '2017-03-27 00:00:00', 'comment on post', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `userId` int(11) NOT NULL,
  `location` varchar(250) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `calendarId` int(11) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `timestamp`, `userId`, `location`, `description`, `calendarId`, `title`) VALUES
(27, '2017-03-27 12:30:00', 1, 'HERE', 'DESCRIPTION', 1, 'EVENT!');

-- --------------------------------------------------------

--
-- Table structure for table `forum`
--

CREATE TABLE `forum` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forum`
--

INSERT INTO `forum` (`id`) VALUES
(1),
(2);

-- --------------------------------------------------------

--
-- Table structure for table `forumpost`
--

CREATE TABLE `forumpost` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `title` varchar(300) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `ratingId` int(11) NOT NULL,
  `tag` varchar(150) NOT NULL,
  `pinned` tinyint(1) NOT NULL,
  `forumId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forumpost`
--

INSERT INTO `forumpost` (`id`, `userId`, `timestamp`, `title`, `description`, `ratingId`, `tag`, `pinned`, `forumId`) VALUES
(10, 1, '2017-03-27 00:00:00', 'post', 'this is a post', 19, 'studyGroup', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `number` int(5) NOT NULL,
  `group_name` varchar(150) NOT NULL,
  `calendarId` int(11) NOT NULL,
  `forumId` int(11) NOT NULL,
  `chatId` int(11) NOT NULL,
  `whiteboardId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `number`, `group_name`, `calendarId`, `forumId`, `chatId`, `whiteboardId`, `userId`) VALUES
(1, 92821, 'CS 4444: CS for Dummies', 1, 1, 1, 1, 1),
(2, 29485, 'CS 9999: Really hard class', 2, 2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `link` varchar(1500) NOT NULL,
  `userId` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `tag` varchar(150) NOT NULL,
  `ratingId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `title`, `link`, `userId`, `timestamp`, `tag`, `ratingId`, `groupId`) VALUES
(3, 'title', 'C:\\xampp\\htdocs\\GobblerExchange\\public\\notes\\test.pdf', 1, '2017-03-26 00:00:00', 'studyGroup', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `poll`
--

CREATE TABLE `poll` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `userId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `isOpen` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `poll`
--

INSERT INTO `poll` (`id`, `title`, `userId`, `groupId`, `timestamp`, `isOpen`) VALUES
(1, 'What is your favorite color?', 1, 1, '2017-03-19 04:14:00', 1),
(2, 'Closed poll', 1, 1, '2017-03-27 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `polloption`
--

CREATE TABLE `polloption` (
  `id` int(11) NOT NULL,
  `pollId` int(11) NOT NULL,
  `poll_option` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `polloption`
--

INSERT INTO `polloption` (`id`, `pollId`, `poll_option`) VALUES
(1, 1, 'Yellow'),
(2, 1, 'Blue'),
(3, 1, 'Green'),
(4, 1, 'Red'),
(28, 3, ''),
(31, 2, 'Option 1'),
(32, 2, 'Option 2');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `id` int(11) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `rating`) VALUES
(19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `email`, `password`) VALUES
(1, 'Freddie Fredson', 'freddie', 'freddie@vt.edu', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE `usergroup` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`id`, `userId`, `groupId`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `userpolloption`
--

CREATE TABLE `userpolloption` (
  `id` int(11) NOT NULL,
  `pollOptionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userpolloption`
--

INSERT INTO `userpolloption` (`id`, `pollOptionId`, `userId`) VALUES
(4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `userrating`
--

CREATE TABLE `userrating` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `ratingId` int(11) NOT NULL,
  `vote` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userrating`
--

INSERT INTO `userrating` (`id`, `userId`, `ratingId`, `vote`) VALUES
(2, 1, 19, 1);

-- --------------------------------------------------------

--
-- Table structure for table `whiteboard`
--

CREATE TABLE `whiteboard` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `whiteboard`
--

INSERT INTO `whiteboard` (`id`) VALUES
(1),
(2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forumpost`
--
ALTER TABLE `forumpost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `poll`
--
ALTER TABLE `poll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `polloption`
--
ALTER TABLE `polloption`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userpolloption`
--
ALTER TABLE `userpolloption`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userrating`
--
ALTER TABLE `userrating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whiteboard`
--
ALTER TABLE `whiteboard`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `forum`
--
ALTER TABLE `forum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `forumpost`
--
ALTER TABLE `forumpost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `poll`
--
ALTER TABLE `poll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `polloption`
--
ALTER TABLE `polloption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `usergroup`
--
ALTER TABLE `usergroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `userpolloption`
--
ALTER TABLE `userpolloption`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `userrating`
--
ALTER TABLE `userrating`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `whiteboard`
--
ALTER TABLE `whiteboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
