<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

// POST request om todos op te slaan
Route::post('/todos/save', [TodoController::class, 'save']);

// GET request om todos op te halen
Route::get('/todos', [TodoController::class, 'getTodos']);