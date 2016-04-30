SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE IF NOT EXISTS `allog_apps` (
  `appname` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `token` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `remote_addr` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  UNIQUE KEY `allog_apps_appname_unique` (`appname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `allog_messages` (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'info',
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `allog_requests_allog` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `http_user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `http_referer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remote_addr` char(15) COLLATE utf8_unicode_ci NOT NULL,
  `request_method` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `request_uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `request_time` datetime DEFAULT NULL,
  `get` text COLLATE utf8_unicode_ci,
  `post` longtext COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
