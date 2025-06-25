<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistTrackController extends Controller
{
    public function store(Request $request, Playlist $playlist)
    {
        $request->validate([
            'track_id' => 'required|exists:tracks,id',
        ]);

        // Prevent duplicate
        if ($playlist->tracks()->where('track_id', $request->track_id)->exists()) {
            return response()->json(['message' => 'Track already in playlist'], 409);
        }

        $playlist->tracks()->attach($request->track_id);

        return response()->json(['message' => 'Track added to playlist']);
    }
}
