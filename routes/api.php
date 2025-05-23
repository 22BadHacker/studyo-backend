<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




// Auth Controller

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// Route::group([
//     "middleware" => ["auth:sanctum"]
// ], function () {
   
//     Route::get('profile', [AuthController::class, 'profile']);
//     Route::get('logout', [AuthController::class, 'logout']);


//    Route::apiResource('users', UserController::class);
//     // Route::apiResource('tracks', TrackController::class);
//     // Route::apiResource('albums', AlbumController::class);
//     // Route::apiResource('playlists', PlaylistController::class);
//     // Route::apiResource('likes', LikeController::class);
//     // Route::apiResource('follows', FollowController::class);

// });


Route::middleware('auth:sanctum')->group(function () {

    Route::get('profile', [AuthController::class, 'profile']);
    Route::get('logout', [AuthController::class, 'logout']);


   Route::apiResource('users', UserController::class);
});