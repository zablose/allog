<?php

declare(strict_types=1);

namespace App\Models;

class Message extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable($this->table()->messages());
    }
}
