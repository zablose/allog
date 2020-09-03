<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zablose\Allog\Table;

class RequestsClientRemote extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(env('ALLOG_DB_PREFIX').Table::TABLE_REQUESTS.env('ALLOG_CLIENT_1_NAME'));
    }
}
