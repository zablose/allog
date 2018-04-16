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

### Certs hints

File 'certs.conf':

```
[req]
req_extensions     = req_ext
distinguished_name = req_distinguished_name
prompt             = no

[req_distinguished_name]
commonName=server.dev

[req_ext]
subjectAltName = @alt_names

[alt_names]
DNS.1 = server.dev
DNS.2 = *.server.dev
```

Command to generate self-signed certificates:

``` 
openssl req -x509 -config ./certs.conf -nodes -days 730 -newkey rsa:2048 -sha256 -keyout server.key -out server.crt
```

Symlink key for system (Debian) to use:

```
ln -sr server.crt /usr/local/share/ca-certificates/server.crt
```

Update system certs:

```
update-ca-certificates
```

## License

This package is free software distributed under the terms of the MIT license.
