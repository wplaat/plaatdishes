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

ALTER TABLE `users` ADD `username` VARCHAR(20) NOT NULL AFTER `active`;
ALTER TABLE `users` ADD `password` VARCHAR(250) NOT NULL AFTER `username`;
ALTER TABLE `users` ADD `last_login` DATETIME NOT NULL AFTER `password`;
ALTER TABLE `users` ADD `admin` INT NOT NULL AFTER `last_login`;
ALTER TABLE `users` CHANGE `pid` `uid` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `dishes` CHANGE `pid` `uid` INT(11) NOT NULL;

DELETE FROM config WHERE token = 'home_username';
DELETE FROM config WHERE token = 'home_password';

