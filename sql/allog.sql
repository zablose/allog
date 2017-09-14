CREATE TABLE IF NOT EXISTS `clients` (
  `name` varchar(16) NOT NULL,
  `token` char(32) NOT NULL,
  `remote_addr` char(15) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  UNIQUE KEY `allog_clients_name_unique` (`name`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL DEFAULT 'info',
  `message` varchar(255) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `requests_allog_server` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `http_user_agent` varchar(255) DEFAULT NULL,
  `http_referer` varchar(255) DEFAULT NULL,
  `remote_addr` char(15) NOT NULL,
  `request_method` char(16) NOT NULL,
  `request_uri` varchar(255) NOT NULL,
  `request_time` datetime DEFAULT NULL,
  `get` text,
  `post` longtext,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
