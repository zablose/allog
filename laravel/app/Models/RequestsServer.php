<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zablose\Allog\Table;

class RequestsServer extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(env('ALLOG_DB_PREFIX').Table::TABLE_REQUESTS.env('ALLOG_SERVER_NAME'));
    }
}
