<?php

/**
 * @noinspection PhpMissingFieldTypeInspection
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zablose\Allog\Table;

class Message extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable((new Table(env('ALLOG_DB_PREFIX')))->messages());
    }
}
