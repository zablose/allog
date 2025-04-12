<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Allog;

class AllogController extends Controller
{

    public function client()
    {
        $client = Allog::client()->send();

        return view('client', compact('client'));
    }

    public function server()
    {
        Allog::server()->run();

        return view('server');
    }
}
