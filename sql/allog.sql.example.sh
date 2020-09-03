#!/usr/bin/env bash

set -e

sudo -- mysql <<EOF

CREATE DATABASE IF NOT EXISTS \`allog\`
    DEFAULT CHARACTER SET utf8
    COLLATE utf8_unicode_ci;
USE \`allog\`;

CREATE TABLE IF NOT EXISTS \`clients\`
(
    \`name\`        VARCHAR(32) NOT NULL,
    \`token\`       CHAR(32)    NOT NULL,
    \`remote_addr\` CHAR(15)    NOT NULL,
    \`active\`      TINYINT(1)  NOT NULL DEFAULT '1',
    \`updated\`     DATETIME    NOT NULL,
    \`created\`     DATETIME    NOT NULL,
    UNIQUE KEY \`allog_clients_name_unique\` (\`name\`)
)
    ENGINE = InnoDB;

INSERT INTO \`clients\`
(name, token, remote_addr, active, updated, created)
VALUES
    ('${ALLOG_CLIENT_NAME}', '${ALLOG_CLIENT_TOKEN}', '${ALLOG_CLIENT_IP}', 1, NOW(), NOW()),
    ('${ALLOG_CLIENT_1_NAME}', '${ALLOG_CLIENT_1_TOKEN}', '${ALLOG_CLIENT_1_IP}', 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS \`messages\`
(
    \`id\`      TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
    \`type\`    VARCHAR(16) NOT NULL DEFAULT 'info',
    \`message\` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
    \`created\` DATETIME NOT NULL,
    PRIMARY KEY (\`id\`)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS \`requests_allog\`
(
    \`id\`              SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    \`http_user_agent\` VARCHAR(255) DEFAULT NULL,
    \`http_referer\`    VARCHAR(2000) DEFAULT NULL,
    \`remote_addr\`     CHAR(15) NOT NULL,
    \`request_method\`  CHAR(16) NOT NULL,
    \`request_uri\`     VARCHAR(2000) NOT NULL,
    \`request_time\`    DATETIME DEFAULT NULL,
    \`get\`             TEXT COLLATE utf8mb4_unicode_ci,
    \`post\`            LONGTEXT COLLATE utf8mb4_unicode_ci,
    \`created\`         DATETIME NOT NULL,
    PRIMARY KEY (\`id\`)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS \`requests_${ALLOG_CLIENT_NAME}\`
(
    \`id\`              SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    \`http_user_agent\` VARCHAR(255) DEFAULT NULL,
    \`http_referer\`    VARCHAR(2000) DEFAULT NULL,
    \`remote_addr\`     CHAR(15) NOT NULL,
    \`request_method\`  CHAR(16) NOT NULL,
    \`request_uri\`     VARCHAR(2000) NOT NULL,
    \`request_time\`    DATETIME DEFAULT NULL,
    \`get\`             TEXT COLLATE utf8mb4_unicode_ci,
    \`post\`            LONGTEXT COLLATE utf8mb4_unicode_ci,
    \`created\`         DATETIME NOT NULL,
    PRIMARY KEY (\`id\`)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS \`requests_${ALLOG_CLIENT_1_NAME}\`
(
    \`id\`              SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    \`http_user_agent\` VARCHAR(255) DEFAULT NULL,
    \`http_referer\`    VARCHAR(2000) DEFAULT NULL,
    \`remote_addr\`     CHAR(15) NOT NULL,
    \`request_method\`  CHAR(16) NOT NULL,
    \`request_uri\`     VARCHAR(2000) NOT NULL,
    \`request_time\`    DATETIME DEFAULT NULL,
    \`get\`             TEXT COLLATE utf8mb4_unicode_ci,
    \`post\`            LONGTEXT COLLATE utf8mb4_unicode_ci,
    \`created\`         DATETIME NOT NULL,
    PRIMARY KEY (\`id\`)
)
    ENGINE = InnoDB;

EOF
