<?php

use App\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zablose\Allog\Db;

class CreateMessagesTable extends Migration
{
    private string $table_name;

    public function __construct()
    {
        $this->table_name = (new Message())->getTable();
    }

    public function up(): void
    {
        Schema::create($this->table_name, function (Blueprint $table)
        {
            $table->tinyIncrements('id');
            $table->string('type', 16)->default(Db::MESSAGE_TYPE_INFO);
            $table->text('message')->collation('utf8mb4_unicode_ci');
            $table->dateTime('created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table_name);
    }
}
