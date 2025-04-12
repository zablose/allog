# Allog

Log all requests to the database, including some data from $_SERVER array, all data from $_GET and $_POST.

> Protected keys from the config will be replaced with '*' for $_POST data only.

## Server Config

| Name                | Value     | Description |
|---------------------|-----------|-------------|
| ALLOG_DEBUG         | false     |             |
| ALLOG_SERVER_NAME   | allog     |             |
| ALLOG_DB_CONNECTION | mysql     |             |
| ALLOG_DB_HOST       | localhost |             |
| ALLOG_DB_PORT       | 3306      |             |
| ALLOG_DB_DATABASE   | allog     |             |
| ALLOG_DB_USERNAME   | allog     |             |
| ALLOG_DB_PASSWORD   |           |             |
| ALLOG_DB_CHARSET    | utf8mb4   |             |
| ALLOG_DB_PREFIX     |           |             |

## Client Config

| Name               | Value                   | Description                                  |
|--------------------|-------------------------|----------------------------------------------|
| ALLOG_DEBUG        | false                   |                                              |
| ALLOG_CLIENT_STATE |                         |                                              |
| ALLOG_CLIENT_NAME  |                         |                                              |
| ALLOG_CLIENT_TOKEN |                         |                                              |
| ALLOG_SERVER_URL   | https://www.allog.zdev/ |                                              |
| ALLOG_PROTECTED_1  | _token                  |                                              |
| ALLOG_PROTECTED_2  | password                |                                              |
| ALLOG_PROTECTED_3  | password_confirmation   |                                              |
| ALLOG_PROTECTED_*  | another_field           | You need to protect of being recorded as is. |

## Development

> Check submodule's [readme](https://github.com/zablose/docker-images/blob/main/readme.md) for more details about
> development environment used.

### Quick start

    $ git clone -b 'dev' --single-branch --depth 1 https://github.com/zablose/allog.git allog
    $ cd allog
    $ git submodule update --init
    
    # Copy env file, then ammend it to your needs.
    $ cp .env.example.dev .env

    # Copy docker compose file, then ammend it, if needed.
    $ cp docker-compose.example.yml docker-compose.yml
    
    $ docker-compose up -d
    
    # To see post-script logs, while container is starting.
    $ tail -f ./laravel/storage/logs/all.log
    
    # To enter container, using Bash shell.
    $ docker exec -it allog-php-fpm bash
    
    (allog-php-fpm)$ php vendor/bin/phpunit

### Build

* Merge changes from your branch to `master`;
* Create a new branch from updated `master` called like `build-3.0.0`;
* Run `php vendor/bin/phing prepare` to remove all irrelevant files and folders;
* Commit changes with message like `Prepare build.`;
* Merge branch `build-3.0.0` to `build` branch;
* Delete `build-*` branch;
* Use tag `3.0.0` on `build` branch to do the release.

> Obviously, replace example `3.0.0` version with yours.
