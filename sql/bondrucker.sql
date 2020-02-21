-- Adminer 4.7.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

USE `bondrucker`;

DELIMITER ;;

DROP EVENT IF EXISTS `Sitzungsbereinigung`;;
CREATE EVENT `Sitzungsbereinigung` ON SCHEDULE EVERY 1 HOUR STARTS '2019-02-20 21:00:00' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Löscht abgelaufene Sitzungen nach 30 Tagen' DO DELETE FROM `sessions` WHERE `lastActivity` < DATE_SUB(NOW(), INTERVAL 30 DAY);;

DELIMITER ;

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `prints`;
CREATE TABLE `prints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Laufende ID',
  `userId` int(10) unsigned NOT NULL COMMENT 'Querverweis - users.id',
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Zeitpunkt des Eintrags',
  `text` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Text',
  `printed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 = noch nicht gedruckt; 1 = gedruckt',
  `printName` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0 = ohne Namen drucken; 1 = mit Namen drucken',
  `publish` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0 = darf nicht veröffentlicht werden; 1 = darf veröffentlicht werden',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `printed` (`printed`),
  CONSTRAINT `prints_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Laufende ID',
  `userId` int(10) unsigned NOT NULL COMMENT 'Querverweis - users.id',
  `sessionhash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sessionhash',
  `lastActivity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Zeitpunkt der letzten Aktivität',
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`),
  KEY `sessionhash` (`sessionhash`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Laufende ID',
  `username` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pr0gramm-Username',
  `lastPrinted` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Zeitpunkt des letzten Druckes',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2020-02-20 21:34:42
