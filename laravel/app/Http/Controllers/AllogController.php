<?php

namespace App\Http\Controllers;

use Zablose\Allog\Client;
use Zablose\Allog\Config;
use Zablose\Allog\Server;

class AllogController extends Controller
{
    public function server()
    {
        (new Server((new Config)->read(__DIR__.'/../../../../.env')))->run();

        return view('server');
    }

    public function client()
    {
        $client = (new Client((new Config())->read(__DIR__.'/../../../../.env')))->send();

        return view('client', compact('client'));
    }
}
