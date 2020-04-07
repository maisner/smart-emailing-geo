-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `sales_point`;
CREATE TABLE `sales_point` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('ticketMachine','ticketOfficeMetro','informationCenter','trainStation','carrierOffice','chipCardDispense') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_hours` json NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lon` decimal(11,8) NOT NULL,
  `services` int(10) unsigned NOT NULL,
  `payment_methods` int(2) unsigned NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `external_id` (`external_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sales_point` (`id`, `type`, `name`, `address`, `opening_hours`, `lat`, `lon`, `services`, `payment_methods`, `remarks`, `link`, `external_id`) VALUES
(1,	'ticketMachine',	'Brno',	'Street 23, Brno',	'[{\"to\": 6, \"from\": 0, \"hours\": \"9:00-17:00\"}]',	49.1917817,	16.6096233,	1,	5,	NULL,	NULL,	'sp1'),
(2,	'ticketMachine',	'Jihlava',	'Street 50, Jihlava',	'[{\"to\": 4, \"from\": 0, \"hours\": \"10:00-17:00\"}]',	49.3961528,	15.5824017,	1,	5,	NULL,	NULL,	'sp2'),
(3,	'ticketMachine',	'Praha',	'Street 87, Praha',	'[{\"to\": 4, \"from\": 0, \"hours\": \"10:00-18:30\"}]',	50.0797556,	14.4297436,	1,	5,	NULL,	NULL,	'sp3'),
(4,	'ticketMachine',	'Aš',	'Street 102, Aš',	'[{\"to\": 6, \"from\": 5, \"hours\": \"09:00-10:00\"}]',	50.2205719,	12.1897450,	1,	5,	NULL,	NULL,	'sp4');
