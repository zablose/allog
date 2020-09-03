<?php

namespace App\Http\Controllers;

use Zablose\Allog\Client;
use Zablose\Allog\Config;
use Zablose\Allog\Server;

class AllogController extends Controller
{
    public function server()
    {
        (new Server((new Config())->read(__DIR__.'/../../../../.env')))->run();

        return view('server');
    }

    public function client()
    {
        $client = (new Client((new Config())->read(__DIR__.'/../../../../.env')))->send();

        return view('client', compact('client'));
    }

    public function server_with_remote_client()
    {
        $_SERVER['REMOTE_ADDR'] = env('ALLOG_CLIENT_1_IP');

        (new Server((new Config())->read(__DIR__.'/../../../../.env')))->run();

        return view('server');
    }
}
