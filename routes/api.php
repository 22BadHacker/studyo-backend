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
    GenreController,
    ProfileController
};

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');




// Auth Controller

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-auth', [AuthController::class, 'googleAuth']);


// Route::get('/artists', [UserController::class, 'getArtists']);

Route::get('/artists', [UserController::class, 'artists']);

Route::get('/users/{public_id}', [UserController::class, 'showByPublicId']);







Route::middleware('auth:sanctum')->group(function () {
    // To upadate profile
    Route::put('/profile', [AuthController::class, 'update']);
    // To get profile
    // Route::get('profile', [AuthController::class, 'profile']);
    // To logout
    Route::get('logout', [AuthController::class, 'logout']);


    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);


   Route::apiResource('users', UserController::class);


   // Profile
    Route::get('/profile/{public_id}', [UserController::class, 'showPublic']);
    
   // Profile
    Route::get('/resolve-profile/{public_id}', [UserController::class, 'resolveProfile']);


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


    Route::post('/follow/{id}', [FollowController::class, 'follow']);
    Route::post('/unfollow/{id}', [FollowController::class, 'unfollow']);
    Route::get('/follow-status/{id}', [FollowController::class, 'check']);

});



// Route::get('/follow/stats/{id}', [FollowController::class, 'stats']);