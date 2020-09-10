<?php /** @noinspection DuplicatedCode */

use App\Models\RequestsClientLocal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsAllogClientLocalTable extends Migration
{
    private string $table_name;

    public function __construct()
    {
        $this->table_name = (new RequestsClientLocal())->getTable();
    }

    public function up(): void
    {
        Schema::create($this->table_name, function (Blueprint $table)
        {
            $table->smallIncrements('id');
            $table->string('http_user_agent', 255)->nullable();
            $table->string('http_referer', 2000)->nullable();
            $table->char('remote_addr', 15);
            $table->char('request_method', 16);
            $table->string('request_uri', 2000);
            $table->dateTime('request_time')->nullable();
            $table->text('get')->collation('utf8mb4_unicode_ci');
            $table->longText('post')->collation('utf8mb4_unicode_ci');
            $table->dateTime('created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->table_name);
    }
}
