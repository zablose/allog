<?php

declare(strict_types=1);

namespace App\Models;

use App\Allog;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(Allog::table()->messages());
    }
}
