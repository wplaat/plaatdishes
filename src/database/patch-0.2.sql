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

UPDATE config SET value="0.2" WHERE token='database_version';

ALTER TABLE `users` ADD `email` VARCHAR(250) NOT NULL AFTER `name`;

ALTER TABLE `users` ADD `active` BOOLEAN NOT NULL AFTER `email`;
UPDATE `users` SET `active` = '1'