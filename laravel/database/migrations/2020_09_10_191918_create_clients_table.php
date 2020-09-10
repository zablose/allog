<?php

use App\Models\Client;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    private string $table_name;

    public function __construct()
    {
        $this->table_name = (new Client())->getTable();
    }

    public function up(): void
    {
        Schema::create($this->table_name, function (Blueprint $table)
        {
            $table->string('name', 32)->unique();
            $table->char('token', 32);
            $table->char('remote_addr', 15);
            $table->boolean('active')->default(true);
            $table->dateTime('updated');
            $table->dateTime('created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table_name);
    }
}
