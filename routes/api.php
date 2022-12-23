<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SpecialityController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\WalletController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::controller(AuthController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');


    Route::group(['middleware' => ['auth:api']], function(){
        Route::post('/logout', 'logout');
    });
});



Route::group(['middleware' => ['auth:api']], function(){
    Route::post('/admins/charge', [UserController::class, 'charge']);
    Route::prefix('/appointments')->controller(AppointmentController::class)->group(function(){
        Route::get('/', 'index');
        Route::get('/schedule', 'schedule');
        Route::post('/{expert}', 'store');
        Route::delete('/{appointment}', 'destroy');
});

    Route::prefix('/ratings')->controller(RatingController::class)->group(function(){
        Route::get('/{expert}', 'index');
        Route::post('/{expert}', 'store');
        Route::put('/{rating}', 'update');
        Route::delete('/{rating}', 'destroy');
        Route::get('/average/{rating}', 'average');
    });

    Route::prefix('/favourites')->controller(FavouriteController::class)->group(function(){
        Route::get('/', 'index');
        Route::post('/{expert}', 'store');
        Route::delete('/{favourite}', 'destroy');
    });


    Route::prefix('/experts')->controller(UserController::class)->group(function(){
        Route::get('/', 'index');
        Route::post('/', 'search');
        Route::get('/{expert}', 'show');
        Route::get('/times/{expert}', 'availableTimes');
    });


    Route::prefix('/specialities')->controller(SpecialityController::class)->group(function(){
        Route::get('/', 'index');
        Route::post('/', 'search');
        Route::get('/{speciality}', 'show');
    });

    Route::prefix('/wallet')->controller(WalletController::class)->group(function(){
        Route::get('/', 'index');
    });

});


