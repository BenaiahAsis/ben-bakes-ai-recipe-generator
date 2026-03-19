<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;

Route::get('/', [RecipeController::class, 'index']);
Route::post('/generate', [RecipeController::class, 'generate'])->name('recipe.generate');