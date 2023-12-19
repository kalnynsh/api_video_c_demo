<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Category\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::get(
        '/api/v1/categories',
        [
            CategoryController::class,
            'index'
        ]
    );

Route::get(
    '/api/v1/categories/{category}',
    [
        CategoryController::class,
        'show'
    ]
);
