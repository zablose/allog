<?php

declare(strict_types=1);

namespace App\Models;

class Client extends Model
{
    protected $primaryKey = 'name';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable($this->table()->clients());
    }
}
