<?php

use App\Models\RequestsClientLocal;
use App\Models\RequestsClientRemote;
use Illuminate\Database\Migrations\Migration;
use Zablose\Allog\Config\Server;
use Zablose\Allog\Db;

class CreateAllogTables extends Migration
{
    public function up(): void
    {
        (new Db((new Server())->read(dirname(__DIR__, 3) . '/.env')))
            ->createTables()
            ->createRequestsTable((new RequestsClientLocal())->getTable())
            ->createRequestsTable((new RequestsClientRemote())->getTable());
    }
}
