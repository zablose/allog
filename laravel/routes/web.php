<?php

use App\Http\Controllers\AllogController;
use Illuminate\Support\Facades\Route;

Route::any('/client', [AllogController::class, 'client']);
Route::any('/server', [AllogController::class, 'server']);
