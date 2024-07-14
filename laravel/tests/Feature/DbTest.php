<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Message;
use App\Models\RequestsClientLocal;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;
use Zablose\Allog\Config\Server as Config;
use Zablose\Allog\Db;

class DbTest extends FeatureTestCase
{
    protected ?Db $db = null;

    public function db(): Db
    {
        if ($this->db === null) {
            $this->db = new Db((new Config())->read(__DIR__.'/../../../.env')->debugOn());
        }

        return $this->db;
    }

    #[Test]
    public function adds_client()
    {
        $this->db()->addClient($name = 'testing_client', 'token', '127.0.0.2');

        $model = Client::where(compact('name'))->first();

        $this->assertTrue($model !== null);

        $model->delete();
    }

    #[Test]
    public function gets_latest_clients()
    {
        $this->db()->addClient($name = 'testing_client', 'token', '127.0.0.2');

        $clients = $this->db()->getLatestClients(1);

        $this->assertIsArray($clients);
        $this->assertSame($name, $clients[0]->name);

        Client::where(compact('name'))->delete();
    }

    #[Test]
    public function gets_latest_requests()
    {
        $uuid = $this->fake()->uuid;

        $data = compact('uuid');

        $this->setGlobalsServerGetPost($uri = '/client', $data);

        $this->post($uri, $data);

        $requests = $this->db()->getLatestRequests(env('ALLOG_CLIENT_NAME'), 1);

        $this->assertIsArray($requests);
        $this->assertStringContainsString($uuid, $requests[0]->post);

        RequestsClientLocal::where((array)$requests[0])->delete();
    }

    #[Test]
    public function adds_info()
    {
        $type = Db::MESSAGE_TYPE_INFO;
        $message = $this->fake()->sentence;

        $this->db()->addInfo($message);

        $model = Message::where(compact('type', 'message'))->first();

        $this->assertNotNull($model);
        $this->assertSame($message, $model->message);

        $model->delete();
    }

    #[Test]
    public function adds_warning()
    {
        $type = Db::MESSAGE_TYPE_WARNING;
        $message = $this->fake()->sentence;

        $this->db()->addWarning($message);

        $model = Message::where(compact('type', 'message'))->first();

        $this->assertNotNull($model);
        $this->assertSame($message, $model->message);

        $model->delete();
    }

    #[Test]
    public function adds_error()
    {
        $type = Db::MESSAGE_TYPE_ERROR;
        $message = $this->fake()->sentence;

        $this->db()->addError($message);

        $model = Message::where(compact('type', 'message'))->first();

        $this->assertNotNull($model);
        $this->assertSame($message, $model->message);

        $model->delete();
    }

    #[Test]
    public function triggers_truncate_table()
    {
        $type = Db::MESSAGE_TYPE_INFO;
        $message = $this->fake()->sentence;

        Message::create(
            [
                'id' => 255,
                'type' => $type,
                'message' => $message,
                'created' => $this->fake()->dateTime,
            ]
        );

        $this->db()->addInfo($message);

        $model = Message::where(['type' => Db::MESSAGE_TYPE_WARNING])->first();
        $this->assertNotNull($model);
        $this->assertStringContainsString('Table was truncated!', $model->message);
        $model->delete();

        $model = Message::where(compact('type', 'message'))->first();
        $this->assertNotNull($model);
        $model->delete();
    }

    #[Test]
    public function rethrows_exception_when_forced_insert_in_use()
    {
        $this->expectExceptionCode('42S02');
        $this->expectExceptionMessage('Base table or view not found');

        $this->db()->addRequest('testing_client', []);
    }
}
