<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::post('/todos/save', [TodoController::class, 'save']);
Route::get('/todos', [TodoController::class, 'getTodos']);