<?php

use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('projects.index');
});

Route::resource('projects', ProjectController::class);
Route::resource('issues', IssueController::class);
Route::resource('tags', TagController::class)->only(['index', 'store']);
