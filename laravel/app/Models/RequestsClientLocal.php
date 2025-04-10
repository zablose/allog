<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zablose\Allog\Table;

class RequestsClientLocal extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable((new Table(env('ALLOG_DB_PREFIX')))->requests(env('ALLOG_CLIENT_NAME')));
    }
}
