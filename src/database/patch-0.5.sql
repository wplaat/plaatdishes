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
(7, 'Raspberry Pi 4B - 4GB + Case + Ventilator + Voeding + 32GB SDCard', 89.85, 'pi.jpg'),
(8, 'Drone', 79.95, 'drone.jpg'),
(9, 'Mobiel Huawei P Smart Black', 148.95, 'huawei-p.jpg'),
(10, 'bol.com bon 25 euro', 24.75, 'bol.com.jpg'),
(11, 'Raspberry Pi 4B - 1GB + Case + Ventilator + Voeding + 32GB SDCard', 68.95, 'pi.jpg');
(12, '25 euro op betaalrekening', 25.00, 'geld.jpg');

CREATE TABLE `sales` (
  `sid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `price` double NOT NULL,
  `timestamp` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

ALTER TABLE `sales` ADD PRIMARY KEY (`sid`);
ALTER TABLE `sales` MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT;