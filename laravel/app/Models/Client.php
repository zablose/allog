<?php

declare(strict_types=1);

namespace App\Models;

use App\Allog;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $primaryKey = 'name';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(Allog::table()->clients());
    }
}
