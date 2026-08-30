<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VoterController;
use App\Http\Controllers\Api\WorkspaceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('/workspaces', WorkspaceController::class);
    Route::apiResource('/voters', VoterController::class);
});
