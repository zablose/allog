<?php

use Illuminate\Support\Facades\Route;

Route::any('/server', 'AllogController@server');
Route::any('/client', 'AllogController@client');
Route::any('/server-with-remote-client', 'AllogController@server_with_remote_client');
