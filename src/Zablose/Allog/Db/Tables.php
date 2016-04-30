<?php

namespace Zablose\Allog\Db;

class Tables
{

    /**
     * Table name for Allog applications.
     */
    const TABLE_APPS = 'allog_apps';

    /**
     * Table name for Allog messages.
     */
    const TABLE_MESSAGES = 'allog_messages';

    /**
     * Base table name for Allog requests tables.
     */
    const TABLE_REQUESTS = 'allog_requests_';

    /**
     * Full table name for Allog applications.
     *
     * @var string
     */
    public $apps;

    /**
     * Full table name for Allog messages.
     *
     * @var string
     */
    public $messages;

    /**
     * Full table name for Allog requests, based on current application name to log for.
     *
     * @var string
     */
    public $requests;

    /**
     * Table names to work with. Prefixed with the 'ALLOG_DB_PREFIX' constant.
     *
     * @param string $appname Current application name to log for.
     */
    public function __construct($appname)
    {
        $this->apps     = ALLOG_DB_PREFIX.static::TABLE_APPS;
        $this->messages = ALLOG_DB_PREFIX.static::TABLE_MESSAGES;
        $this->requests = ALLOG_DB_PREFIX.static::TABLE_REQUESTS.$appname;
    }

}
