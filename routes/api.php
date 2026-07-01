<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LinkedRepoApiController;

Route::middleware(['auth:sanctum', 'throttle:30,1'])->group(function () {
    Route::post('/links', [LinkedRepoApiController::class, 'store']);
});
