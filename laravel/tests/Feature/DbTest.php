<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Clients;
use App\Models\RequestsClientLocal;
use Tests\TestCase;
use Zablose\Allog\Config;
use Zablose\Allog\Db;

class DbTest extends TestCase
{
    protected ?Db $db = null;

    public function db(): Db
    {
        if ($this->db === null) {
            $this->db = new Db((new Config())->read(__DIR__.'/../../../.env')->debugOn());
        }

        return $this->db;
    }

    /** @test */
    public function adds_client()
    {
        $this->db()->addClient($name = 'testing_client', 'token', '127.0.0.2');

        $model = Clients::where(compact('name'))->first();

        $this->assertTrue($model !== null);

        $model->delete();
    }

    /** @test */
    public function gets_latest_clients()
    {
        $this->db()->addClient($name = 'testing_client', 'token', '127.0.0.2');

        $clients = $this->db()->getLatestClients(1);

        $this->assertIsArray($clients);
        $this->assertSame($name, $clients[0]->name);

        Clients::where(compact('name'))->delete();
    }

    /** @test */
    public function gets_latest_requests()
    {
        $uuid = $this->fake()->uuid;

        $this->post('/client', compact('uuid'));

        $requests = $this->db()->getLatestRequests(env('ALLOG_CLIENT_NAME'), 1);

        $this->assertIsArray($requests);
        $this->assertStringContainsString($uuid, $requests[0]->post);

        RequestsClientLocal::where((array) $requests[0])->delete();
    }
}
