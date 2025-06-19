<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    // public function search(Request $request)
    // {
    //     try {
    //         $query = $request->query('q');

    //         if (!$query) {
    //             return response()->json([], 200);
    //         }

    //         $tracks = Track::where('title', 'like', "%$query%")->get();
    //         $albums = Album::where('title', 'like', "%$query%")->get();
    //         $artists = User::where('role', 'artist')
    //             ->where('username', 'like', "%$query%")
    //             ->get();

    //         return response()->json([
    //             'tracks' => $tracks,
    //             'albums' => $albums,
    //             'artists' => $artists,
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Search Error: ' . $e->getMessage());
    //         return response()->json(['error' => 'Something went wrong'], 500);
    //     }
    // }


    public function search(Request $request)
    {
        $query = $request->query('q');

        if (!$query) {
            return response()->json([], 200);
        }

        $tracks = Track::with('user')->where('title', 'like', "%$query%")->get();
        $albums = Album::with('user')->where('title', 'like', "%$query%")->get();
        $artists = User::where('role', 'artist')
            ->where('username', 'like', "%$query%")
            ->get();

        return response()->json([
            'tracks' => $tracks,
            'albums' => $albums,
            'artists' => $artists,
        ]);
    }
}
