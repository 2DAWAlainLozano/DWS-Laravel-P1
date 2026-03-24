<?php

use App\Http\Controllers\Api\GameApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/games', [GameApiController::class, 'published']);
    Route::post('/games/{game}/session-start', [GameApiController::class, 'sessionStart']);
    Route::post('/games/{game}/events', [GameApiController::class, 'event']);
});
