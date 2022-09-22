<?php

use App\Http\Controllers\API as Controller;
use Illuminate\Support\Facades\Route;

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

Route::middleware('guest')->group(static function () {
	Route::post('auth/login', [ Controller\AuthController::class, 'login' ])->name('auth.login');
});

Route::middleware('auth:api')->group(static function () {
	Route::post('auth/logout', [ Controller\AuthController::class, 'logout' ])->name('auth.logout');
	Route::post('auth/refresh', [ Controller\AuthController::class, 'refresh' ])->name('auth.refresh');
	Route::get('current', [ Controller\WeatherController::class, 'getCurrent' ])->name('weather.current');
	Route::get('forecast', [ Controller\WeatherController::class, 'getForecast' ])->name('weather.forecast');
	Route::get('forecast/{date}', [ Controller\WeatherController::class, 'getForecast' ])->name('weather.forecast.date');
	Route::get('forecast/{date}/hourly', [ Controller\WeatherController::class, 'getForecast' ])->name('weather.forecast.date.hourly');
});
