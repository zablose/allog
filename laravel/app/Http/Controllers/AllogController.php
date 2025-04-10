<?php

namespace App\Http\Controllers;

use Zablose\Allog\Client;
use Zablose\Allog\Config\Client as ClientConfig;
use Zablose\Allog\Config\Server as ServerConfig;
use Zablose\Allog\Server;

class AllogController extends Controller
{
    public function server()
    {
        (new Server((new ServerConfig())->read(dirname(__DIR__, 4) . '/.env')))->run();

        return view('server');
    }

    public function client()
    {
        $client = (new Client((new ClientConfig())->read(dirname(__DIR__, 4) . '/.env')))->send();

        return view('client', compact('client'));
    }

    public function server_with_remote_client()
    {
        (new Server((new ServerConfig())->read(dirname(__DIR__, 4) . '/.env')))->run();

        return view('server');
    }
}
