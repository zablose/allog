# Allog

Log all requests to the database, including some data from $_SERVER array, all data from $_GET and $_POST.

> Protected keys from the config will be replaced with '*' for $_POST data only.

## Server Config

| Name | Value | Description |
| --- | --- | --- |
| ALLOG_DEBUG | false |  |
| ALLOG_SERVER_NAME | allog |  |
| ALLOG_DB_CONNECTION | mysql |  |
| ALLOG_DB_HOST | localhost |  |
| ALLOG_DB_PORT | 3306 |  |
| ALLOG_DB_DATABASE | allog |  |
| ALLOG_DB_USERNAME | allog |  |
| ALLOG_DB_PASSWORD |  |  |
| ALLOG_DB_CHARSET | utf8mb4 |  |
| ALLOG_DB_PREFIX |  |  |

## Client Config

| Name | Value | Description |
| --- | --- | --- |
| ALLOG_DEBUG | false |  |
| ALLOG_CLIENT_STATE |  |  |
| ALLOG_CLIENT_NAME |  |  |
| ALLOG_CLIENT_TOKEN |  |  |
| ALLOG_SERVER_URL | https://www.allog.zdev/ |  |
| ALLOG_PROTECTED_1 | _token |  |
| ALLOG_PROTECTED_2 | password |  |
| ALLOG_PROTECTED_3 | password_confirmation |  |
| ALLOG_PROTECTED_* | another_field | You need to protect of being recorded as is. |

## License

This package is free software distributed under the terms of the MIT license.
