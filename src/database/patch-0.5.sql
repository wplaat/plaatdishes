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
--  All copyrights reserved (c) 1996-2020 PlaatSoft
--

UPDATE config SET value="0.5" WHERE token='database_version';

TRUNCATE market_place;

INSERT INTO `market_place` (`mid`, `description`, `price`, `image`) VALUES
(1, 'Geld op betaalrekening', 5.00, '5euro.png'),
(2, 'Geld op betaalrekening', 20.00, '20euro.png'),
(3, 'Geld op betaalrekening', 50.00, '50euro.png'),
(4, 'Geld op betaalrekening', 100.00, '100euro.png'),
(5, 'Geld op betaalrekening', 200.00, '200euro.png'),
(6, 'Geld op betaalrekening', 500.00, '500euro.png');

CREATE TABLE `sales` (
  `sid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `price` double NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `sales` ADD PRIMARY KEY (`sid`);
ALTER TABLE `sales` MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT;