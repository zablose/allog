<?php

use Illuminate\Support\Facades\Route;

Route::any('/', 'AllogController@server');
Route::any('/client', 'AllogController@client');
