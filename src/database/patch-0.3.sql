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

UPDATE config SET value="0.3" WHERE token='database_version';

ALTER TABLE `users` ADD `username` VARCHAR(20) NOT NULL AFTER `active`;
ALTER TABLE `users` ADD `password` VARCHAR(250) NOT NULL AFTER `username`;
ALTER TABLE `users` ADD `last_login` DATETIME NOT NULL AFTER `password`;
ALTER TABLE `users` ADD `admin` INT NOT NULL AFTER `last_login`;
ALTER TABLE `users` CHANGE `pid` `uid` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` ADD `sid` INT NOT NULL AFTER `admin`;

ALTER TABLE `dishes` CHANGE `pid` `uid` INT(11) NOT NULL;

DELETE FROM config WHERE token = 'home_username';
DELETE FROM config WHERE token = 'home_password';

ALTER TABLE `session` ADD `uid` INT NOT NULL AFTER `ip`;

ALTER TABLE `users` CHANGE `sid` `session_id` VARCHAR(250) NOT NULL;

-- 

CREATE TABLE `transaction` (
  `tid` int(11) NOT NULL,
  `date` date NOT NULL,
  `uid` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `transaction` ADD PRIMARY KEY (`tid`);
ALTER TABLE `transaction` CHANGE `tid` `tid` INT(11) NOT NULL AUTO_INCREMENT;

INSERT INTO transaction (date, uid, amount) SELECT date, uid, total as amount FROM dishes;

ALTER TABLE `dishes` DROP `hash`;
ALTER TABLE `dishes` DROP `total`;



