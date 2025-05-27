<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return Playlist::with('tracks')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $playlist = Playlist::create($request->all());

        return response()->json($playlist, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function addTrack(Request $request, Playlist $playlist)
    {
        $request->validate([
            'track_id' => 'required|exists:tracks,id',
        ]);

        // Attach track to playlist (prevent duplicates)
        $playlist->tracks()->syncWithoutDetaching($request->track_id);

        return response()->json(['message' => 'Track added to playlist']);
    }

    public function removeTrack(Playlist $playlist, Track $track)
    {
        $playlist->tracks()->detach($track->id);

        return response()->json(['message' => 'Track removed from playlist']);
    }
}
