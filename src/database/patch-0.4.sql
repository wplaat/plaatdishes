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

UPDATE config SET value="0.4" WHERE token='database_version';

CREATE TABLE `market_place` (
  `mid` int(11) NOT NULL,
  `description` varchar(250) NOT NULL,
  `price` double NOT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `market_place` (`mid`, `description`, `price`, `image`) VALUES
(1, 'Simpel simkaart 5 euro', 5, 'simpel_sim.jpg'),
(2, 'KPN sim kaart 10 euro', 10, 'kpn_sim.jpg'),
(3, 'Vodafone sim kaart 10 euro', 10, 'vodafone_sim.jpg'),
(4, 'Ben sim kaart 25 euro', 25, 'ben_sim.jpg'),
(5, 'Tmobile sim kaart 25 euro', 25, 'tmobile_sim.jpg');

ALTER TABLE `market_place` ADD PRIMARY KEY (`mid`);
ALTER TABLE `market_place` MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;