<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return auth()->user()->playlists()->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('playlists', 'public');
        }

        $playlist = Playlist::create([
            'name' => $request->name,
            'cover_image' => $imagePath,
            'user_id' => auth()->id(),
        ]);

        return response()->json($playlist, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($public_id)
    {
        $playlist = Playlist::where('public_id', $public_id)->with('tracks')->firstOrFail();
        return response()->json($playlist);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $public_id)
    {
        $playlist = Playlist::where('public_id', $public_id)->firstOrFail();

        if ($playlist->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            // Optionally delete old image
            if ($playlist->cover_image) {
                Storage::disk('public')->delete($playlist->cover_image);
            }

            $playlist->cover_image = $request->file('cover_image')->store('playlists', 'public');
        }

        if ($request->name) {
            $playlist->name = $request->name;
        }

        $playlist->save();

        return response()->json($playlist);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($public_id)
    {
        $playlist = Playlist::where('public_id', $public_id)->firstOrFail();

        if ($playlist->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $playlist->delete();

        return response()->json(['message' => 'Playlist deleted']);
    }


    // public function addTrack(Request $request, $public_id)
    // {
    //     $request->validate([
    //         'track_id' => 'required|exists:tracks,id',
    //     ]);

    //     $playlist = Playlist::where('public_id', $public_id)->firstOrFail();

    //     if ($playlist->user_id !== auth()->id()) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }

    //     $playlist->tracks()->syncWithoutDetaching([$request->track_id]);

    //     return response()->json(['message' => 'Track added to playlist.']);
    // }

    // public function removeTrack(Request $request, $public_id)
    // {
    //     $request->validate([
    //         'track_id' => 'required|exists:tracks,id',
    //     ]);

    //     $playlist = Playlist::where('public_id', $public_id)->firstOrFail();

    //     if ($playlist->user_id !== auth()->id()) {
    //         return response()->json(['error' => 'Unauthorized'], 403);
    //     }

    //     $playlist->tracks()->detach($request->track_id);

    //     return response()->json(['message' => 'Track removed from playlist.']);
    // }


    public function addTrack(Request $request, $playlistId)
    {
        $request->validate([
            'track_id' => 'required|exists:tracks,id',
        ]);

        $playlist = Playlist::findOrFail($playlistId);

        // Optional: Check if user owns the playlist
        if ($playlist->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Attach track (will not duplicate due to unique constraint)
        $playlist->tracks()->syncWithoutDetaching([$request->track_id]);

        return response()->json(['message' => 'Track added successfully']);
    }


    public function removeTrack(Request $request, $playlistId)
    {
        $request->validate([
            'track_id' => 'required|exists:tracks,id',
        ]);

        $playlist = Playlist::findOrFail($playlistId);

        if ($playlist->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $playlist->tracks()->detach($request->track_id);

        return response()->json(['message' => 'Track removed successfully']);
    }



}
