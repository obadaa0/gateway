<?php

use App\Http\Controllers\GatewayController;
use Illuminate\Support\Facades\Route;

Route::any('{any}', [GatewayController::class, 'handle'])->where('any', '.*');
