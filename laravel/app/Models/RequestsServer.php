<?php

declare(strict_types=1);

namespace App\Models;

class RequestsServer extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable($this->table()->requestsClient(env('ALLOG_SERVER_NAME')));
    }
}
