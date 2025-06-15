<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $albums = $user->albums()->with('tracks')->get();
        $tracks = $user->tracks()->with('album')->get();
        $playlists = $user->playlists()->with('tracks')->get();

        return response()->json([
            'albums' => $albums,
            'tracks' => $tracks,
            'playlists' => $playlists,
        ]);
    }
}
