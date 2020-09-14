<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table((new Client())->getTable())->insert(
            require_once __DIR__.'/data/clients.php'
        );
    }
}
