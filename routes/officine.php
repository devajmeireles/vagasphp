<?php

use App\Http\Middleware\AuthenticateOfficine;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthenticateOfficine::class)
    ->prefix('/officine')
    ->name('officine.')
    ->group(function () {
        Route::get('/', function () {
            return 'Hello, Officine!';
        })->name('index');
    });
