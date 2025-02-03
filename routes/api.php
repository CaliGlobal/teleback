<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\LatestEpisodeController;
use App\Http\Controllers\NewsCastController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ShowsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login' , LoginController::class);


//Website acceess

Route::get('tele-news', [NewsController::class, 'index']); // Public access to news listing
Route::get('tele-news-cast', [NewsCastController::class, 'index']); // Public access to news listing
Route::get('newsflash', [NewsController::class, 'newsflash']); // Public access to news listing
Route::get('tele-latest-episode', [LatestEpisodeController::class, 'index']); // Public access to news listing

Route::get('tele-carousel', [CarouselController::class, 'index']); // Public access to news listing
Route::get('tele-getNearestPrograms', [ShowsController::class, 'getNearestPrograms']); // Public access to news listing
Route::get('tele-getAllShowsWithSchedules', [ShowsController::class, 'getAllShowsWithSchedules']); // Public access to news listing

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('news', NewsController::class);
    Route::apiResource('news-cast', NewsCastController::class);
    Route::apiResource('latest-episode', LatestEpisodeController::class);
    Route::apiResource('shows', ShowsController::class);
    Route::apiResource('carousel', CarouselController::class);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
