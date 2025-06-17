<?php

use App\Http\Controllers\AuthController;
// use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    UserController,
    TrackController,
    AlbumController,
    DashboardController,
    PlaylistController,
    FollowController,
    LikeController,
    LibraryController,
    GenreController,
    ProfileController,
    SearchController
};






// Auth Controller

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-auth', [AuthController::class, 'googleAuth']);


Route::get('/artists', [UserController::class, 'artists']);

Route::get('/users/{public_id}', [UserController::class, 'showByPublicId']);
Route::get('/albums/{public_id}', [AlbumController::class, 'showByPublicId']);



Route::get('/users/{public_id}/albums', [AlbumController::class, 'getByPublicId']);

Route::get('/search', [SearchController::class, 'search']);




Route::middleware('auth:sanctum')->group(function () {

    Route::get('/my-albums', [AlbumController::class, 'myAlbums']);
    
    // To upadate profile
    Route::put('/profile', [AuthController::class, 'update']);
    Route::post('/profile/image', [AuthController::class, 'updateImage']);

    // To logout
    Route::get('logout', [AuthController::class, 'logout']);
    
    Route::post('/user/update-profile', [UserController::class, 'updateProfile']);
    // Route::post('/user/update', [UserController::class, 'update']);


    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/remove-image', [ProfileController::class, 'removeImage']);


   Route::apiResource('users', UserController::class);


   // Profile
    Route::get('/profile/{public_id}', [UserController::class, 'showPublic']);
    
   // Profile
    Route::get('/resolve-profile/{public_id}', [UserController::class, 'resolveProfile']);


    // Tracks
    // Route::apiResource('tracks', TrackController::class);
    Route::post('/tracks', [TrackController::class, 'store']);

    // Albums
    Route::apiResource('albums', AlbumController::class);

    // Playlist routes (only index & store)
    Route::get('/playlists', [PlaylistController::class, 'index']);
    Route::post('/playlists', [PlaylistController::class, 'store']);


    // Custom routes to manage tracks in a playlist
    Route::post('/playlists/{playlist}/tracks', [PlaylistController::class, 'addTrack']);
    Route::delete('/playlists/{playlist}/tracks/{track}', [PlaylistController::class, 'removeTrack']);


    // Genres
    // Route::apiResource('genres', GenreController::class)->only(['index', 'show']);


    Route::post('/follow/{id}', [FollowController::class, 'follow']);
    Route::post('/unfollow/{id}', [FollowController::class, 'unfollow']);
    Route::get('/follow-status/{id}', [FollowController::class, 'check']);



    Route::get('/dashboard', [DashboardController::class, 'index']);



    Route::post('/tracks/{id}/toggle-popular', [TrackController::class, 'togglePopular']);

    // new User Profilees 
    Route::get('/user/profile', [UserController::class, 'edit']);
    Route::put('/user/profile', [UserController::class, 'update']);

});


Route::get('/users/{public_id}/tracks', [TrackController::class, 'getByUser']);


    
    

Route::get('/my-tracks', [UserController::class, 'myTracks']);


Route::get('/my-tracks/preview', [TrackController::class, 'preview']);

Route::get('/genres', [GenreController::class, 'index']);
Route::post('/genres', [GenreController::class, 'store']); 


Route::get('/tracks', [TrackController::class, 'index']);

// Route::get('/follow/stats/{id}', [FollowController::class, 'stats']);