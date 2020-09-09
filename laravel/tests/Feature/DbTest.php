<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Clients;
use Tests\TestCase;
use Zablose\Allog\Config;
use Zablose\Allog\Db;

class DbTest extends TestCase
{
    /** @test */
    public function adds_client()
    {
        (new Db((new Config())->read(__DIR__.'/../../../.env')->debugOn()))
            ->addClient($name = 'testing_client', 'token', '127.0.0.2');

        $model = Clients::where(compact('name'))->first();

        $this->assertTrue($model !== null);

        $model->delete();
    }
}
