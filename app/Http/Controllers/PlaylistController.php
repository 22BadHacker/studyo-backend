<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Playlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    // 🔹 Create new playlist
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'is_public' => 'required|boolean',
        ]);

        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->user_id = $request->user()->id;
        $playlist->description = $request->description;
        $playlist->is_public = $request->boolean('is_public');

        if ($request->hasFile('cover_image')) {
            $playlist->cover_image = $request->file('cover_image')->store('playlist_covers', 'public');
        }

        $playlist->save();

        return response()->json($playlist, 201);
    }

    // 🔹 Get all playlists by user
    public function userPlaylists($id)
    {
        $playlists = Playlist::where('user_id', $id)->with('tracks')->get();
        return response()->json($playlists);
    }

    // public function getByPublicId($public_id)
    // {
    //     $playlists = Playlist::where('public_id', $public_id)
    //                     ->with('tracks', 'user') 
    //                     ->get()
    //                     ->firstOrFail();


    //     return response()->json($playlists);
    // }



    public function getByPublicId($public_id)
    {
        $playlist = Playlist::where('public_id', $public_id)
        ->with(['tracks', 'user'])
        ->firstOrFail();

        $albums = Album::where('user_id', $playlist->user_id)
            ->with('tracks', 'user') 
            ->get();

        // Get more playlists by the same user
        $morePlaylists = Playlist::where('user_id', $playlist->user_id)
            ->where('id', '!=', $playlist->id)
            ->take(8)
            ->get();

        return response()->json([
            'playlist' => $playlist,
            'albums' => $albums,
            'more_playlists' => $morePlaylists,
        ]);
    }

    
}


// $albums = Album::where('$public_id', $public_id)
//             ->with('tracks', 'user') 
//             ->get()
//             ->firstOrFail();

//         return response()->json($albums);

//  $moreAlbums = Album::where('user_id', $album->user_id)
//             ->where('id', '!=', $album->id)
//             ->take(6)
//             ->get();