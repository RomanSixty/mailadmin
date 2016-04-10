/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE TABLE IF NOT EXISTS `accounts` (
  `id_user` int(9) NOT NULL AUTO_INCREMENT,
  `local_part` varchar(255) NOT NULL DEFAULT '',
  `domain` int(16) NOT NULL,
  `forward` varchar(255) NOT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `pwclear` varchar(255) NOT NULL DEFAULT '',
  `pwcrypt` varchar(255) NOT NULL DEFAULT '',
  `is_away` tinyint(1) NOT NULL DEFAULT '0',
  `away_subject` text,
  `away_text` text,
  `spam_check` enum('yes','no') NOT NULL DEFAULT 'no',
  `spam_purge` enum('yes','no') NOT NULL DEFAULT 'no',
  `virus_check` enum('yes','no') NOT NULL DEFAULT 'no',
  `is_enabled` enum('yes','no') NOT NULL DEFAULT 'yes',
  `created_at` int(16) NOT NULL DEFAULT '0',
  `updated_at` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `UNIQUE_EMAIL` (`domain`,`local_part`),
  CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`domain`) REFERENCES `domains` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(255) NOT NULL,
  `host` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `domain` (`domain_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `hosts` (
  `id_host` int(16) NOT NULL AUTO_INCREMENT,
  `host_name` varchar(255) NOT NULL,
  `passwd` char(32) DEFAULT NULL,
  `notes` text NOT NULL,
  `admin` enum('no','yes') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id_host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
