<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Track;
use App\Models\User;
use Illuminate\Http\Request;

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


    public function search(Request $request)
    {
        $q = $request->query('q');

        $artists = User::where('name', 'like', "%$q%")->limit(5)->get();
        $albums = Album::where('title', 'like', "%$q%")->limit(5)->get();
        $tracks = Track::where('title', 'like', "%$q%")->limit(5)->get();

        return response()->json([
            'artists' => $artists,
            'albums' => $albums,
            'tracks' => $tracks,
        ]);
    }
}
