<?php

use App\Http\Controllers\AllogController;
use Illuminate\Support\Facades\Route;

Route::any('/server', [AllogController::class, 'server']);
Route::any('/client', [AllogController::class, 'client']);
Route::any('/server-with-remote-client', [AllogController::class, 'server_with_remote_client']);
