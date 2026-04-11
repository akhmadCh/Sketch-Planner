<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/meyerhof_sementara', function () {
    return view('meyerhof_sementara');
})->name('meyerhof_sementara');
