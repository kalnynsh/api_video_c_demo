<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Video\VideoController;
use App\Http\Controllers\Channel\ChannelController;
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

Route::middleware(['api'])
    ->prefix('/api/v1')
    ->group(function () {
        Route::get('/users',
            [
                UserController::class,
                'index',
            ]
        );

        Route::get('/users/{user}',
            [
                UserController::class,
                'show',
            ]
        );

        Route::get('/categories',
            [
                CategoryController::class,
                'index',
            ]
        );

        Route::get('/categories/{category}',
            [
                CategoryController::class,
                'show',
            ]
        );

        Route::get('/channels',
            [
                ChannelController::class,
                'index',
            ]
        );

        Route::get('/channels/{channel}',
            [
                ChannelController::class,
                'show',
            ]
        );

        Route::get('/videos',
            [
                VideoController::class,
                'index',
            ]
        );

        Route::get('/videos/{video}',
            [
                VideoController::class,
                'show',
            ]
        );
    });
