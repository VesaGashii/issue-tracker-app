<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('projects.index');
});

Route::resource('projects', ProjectController::class);
