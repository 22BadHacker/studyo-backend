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


// related artist
Route::get('/artist/{public_id}', [UserController::class, 'showArtist']);




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
    // Route::post('/playlists/{public_id}/add-track', [PlaylistController::class, 'addTrack']);
    // Route::post('/playlists/{public_id}/remove-track', [PlaylistController::class, 'removeTrack']);
    Route::post('/playlists/{playlist}/add-track', [PlaylistController::class, 'addTrack']);
    Route::delete('/playlists/{playlist}/remove-track', [PlaylistController::class, 'removeTrack']);



    Route::get('/playlists', [PlaylistController::class, 'index']);           // Get all playlists for auth user
    Route::post('/playlists', [PlaylistController::class, 'store']);          // Create new playlist
    Route::get('/playlists/{public_id}', [PlaylistController::class, 'show']); // Get a playlist by public_id
    Route::put('/playlists/{public_id}', [PlaylistController::class, 'update']); // Update playlist
    Route::delete('/playlists/{public_id}', [PlaylistController::class, 'destroy']); // Delete playlist

    // Follow
    Route::post('/follow/{id}', [UserController::class, 'follow']);
    Route::post('/unfollow/{id}', [UserController::class, 'unfollow']);
    Route::get('/is-following/{id}', [UserController::class, 'isFollowing']);



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




Route::get('/users/{id}/followed-artists', [UserController::class, 'followedArtists']);
Route::get('/users/{public_id}/following', [UserController::class, 'following']);

