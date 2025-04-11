<?php

declare(strict_types=1);

namespace App\Models;

use App\Allog;
use Illuminate\Database\Eloquent\Model;

class RequestsClientRemote extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(Allog::table()->requestsClient(env('ALLOG_CLIENT_1_NAME')));
    }
}
