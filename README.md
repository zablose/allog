# Allog

Log all requests to the database, including some data from $_SERVER array, all data from $_GET and all, yet protected, data from $_POST.

> Protected means, that values of the selected keys from the config will be replaced with '*'.

Data from $_GET and $_POST arrays stored in DB as JSON strings.

## Usage

In order to make it work, you have to setup a server first, then setup client(s).

### Server

* Use provided 'allog.sql' to create DB and tables.
* Create 'requests_{project_name}' tables for every client. Use 'requests_allog_server' as a template.
* Add clients to clients' table.
* Grant privileges for 'allog' user.

```sql
GRANT ALL PRIVILEGES ON allog.* To 'allog'@'%' IDENTIFIED BY 'password';
FLUSH PRIVILEGES;
```

* Setup the Allog server.
* Server's 'index.php' file may look like:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

(new \Zablose\Allog\Server(require_once __DIR__ . '/../.allog.server.config.php'))->run();

```

* Copy "pasta" '.allog.server.config.template.php' to server's root as '.allog.server.config.php'.

### Client

Add the code below somewhere in your project. Best place is the project's entry point like 'index.php'.

```php
<?php

// ...

require __DIR__ . '/../vendor/autoload.php';

// ...

(new Zablose\Allog\Client(require_once __DIR__ . '/../.allog.client.config.php'))->send();

// ...

```

Copy '.allog.client.config.template.php' to your project's root as '.allog.client.config.php'.

### Apache2 config example for Allog server

```apacheconfig
<IfModule mod_ssl.c>
    <VirtualHost *:443>
        ServerName allog.domain.dev
        ServerAdmin admin@domain.com
        DocumentRoot /var/www/allog/public

        <Directory "/var/www/allog/public">
            RewriteEngine on
            RewriteBase /
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
        </Directory>

        # Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
        # error, crit, alert, emerg.
        # It is also possible to configure the loglevel for particular
        # modules, e.g.
        LogLevel info ssl:warn

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
        
        SSLEngine on
        SSLCertificateFile  /etc/ssl/certs/ssl-cert-snakeoil.pem
        SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key
        
        <FilesMatch "\.(cgi|shtml|phtml|php)$">
            SSLOptions +StdEnvVars
        </FilesMatch>
        <Directory /usr/lib/cgi-bin>
            SSLOptions +StdEnvVars
        </Directory>
        
    </VirtualHost>
</IfModule>

# vim: syntax=apache ts=4 sw=4 sts=4 sr noet

```

## License

This package is free software distributed under the terms of the MIT license.
