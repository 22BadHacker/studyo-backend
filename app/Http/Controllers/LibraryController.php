<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $tracks = $user->tracks()->with('album')->latest()->get();
        $albums = $user->albums()->latest()->get();
        $playlists = $user->playlists()->latest()->get();

        return response()->json([
            'tracks' => $tracks,
            'albums' => $albums,
            'playlists' => $playlists,
            'user' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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


    // public function userLibrary($userId)
    // {
    //     return [
    //         'tracks' => LibraryTrack::where('user_id', $userId)->with('track')->get(),
    //         'playlists' => LibraryPlaylist::where('user_id', $userId)->with('playlist')->get(),
    //     ];
    // }
}
