<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
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


     public function likeTrack(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'track_id' => 'required|exists:tracks,id'
        ]);

        $like = Like::create($request->all());

        return response()->json($like, 201);
    }


    public function userLikes($userId)
    {
        return Like::where('user_id', $userId)->with('track')->get();
    }
}
