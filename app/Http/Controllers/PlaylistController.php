<?php

namespace App\Http\Controllers;

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
        ]);

        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->user_id = $request->user()->id;

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

    public function getByPublicId($public_id)
    {
        $user = User::where('public_id', $public_id)->firstOrFail();
        $playlists = $user->playlists()->with('tracks')->get();

            if ($user->role !== 'artist') {
            return response()->json([], 403);
            }

        return response()->json($playlists);
    }

    
}
