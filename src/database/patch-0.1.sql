--
--  ============
--  PlaatDishes
--  ============
--
--  Created by wplaat
--
--  For more information visit the following website.
--  Website : www.plaatsoft.nl 
--
--  Or send an email to the following address.
--  Email   : info@plaatsoft.nl
--
--  All copyrights reserved (c) 1996-2019 PlaatSoft
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `token` varchar(32) NOT NULL,
  `value` varchar(128) NOT NULL,
  `options` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `readonly` tinyint(1) NOT NULL,
  `rebuild` int(11) NOT NULL,
  `encrypt` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


INSERT INTO `config` (`id`, `category`, `token`, `value`, `options`, `date`, `readonly`, `rebuild`, `encrypt`) VALUES
(1, 0, 'home_password', '', '', '2019-09-01', 0, 0, 1),
(2, 0, 'home_username', '', '', '2019-08-25', 0, 0, 0),
(3, 0, 'database_version', '0.1', '', '0000-00-00', 1, 0, 0);

CREATE TABLE `dishes` (
  `did` int(11) NOT NULL,
  `date` date NOT NULL,
  `pid` int(11) NOT NULL,
  `task1` int(11) NOT NULL,
  `task2` int(11) NOT NULL,
  `task3` int(11) NOT NULL,
  `task4` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `hash` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `session` (
  `sid` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `timestamp` datetime NOT NULL,
  `requests` int(11) NOT NULL,
  `language` varchar(10) DEFAULT NULL,
  `theme` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `pid` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`pid`, `name`) VALUES
(1, 'kid1'),
(2, 'kid2'),
(3, 'kid3'),
(4, 'kid4');

ALTER TABLE `config` ADD PRIMARY KEY (`id`);
ALTER TABLE `dishes` ADD PRIMARY KEY (`did`);
ALTER TABLE `session` ADD PRIMARY KEY (`sid`);
ALTER TABLE `users` ADD PRIMARY KEY (`pid`);

ALTER TABLE `config` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `dishes` MODIFY `did` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `session` MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `users` MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;