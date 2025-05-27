<?php

use App\Http\Controllers\AuthController;
// use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    UserController,
    TrackController,
    AlbumController,
    PlaylistController,
    FollowController,
    LikeController,
    LibraryController,
    GenreController
};

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




// Auth Controller

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-auth', [AuthController::class, 'googleAuth']);







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


   // Profile
    Route::get('/profile/{public_id}', [UserController::class, 'showPublic']);


    // Tracks
    Route::apiResource('tracks', TrackController::class);

    // Albums
    Route::apiResource('albums', AlbumController::class);

    // Playlist routes (only index & store)
    Route::get('/playlists', [PlaylistController::class, 'index']);
    Route::post('/playlists', [PlaylistController::class, 'store']);


    // Custom routes to manage tracks in a playlist
    Route::post('/playlists/{playlist}/tracks', [PlaylistController::class, 'addTrack']);
    Route::delete('/playlists/{playlist}/tracks/{track}', [PlaylistController::class, 'removeTrack']);


    // Genres
    Route::apiResource('genres', GenreController::class)->only(['index', 'show']);

});