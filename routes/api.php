<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Calculation\MeyerhofController;
Route::post('/projects/{id}/calculate/meyerhof', [MeyerhofController::class, 'calculate']);
