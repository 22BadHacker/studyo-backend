<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($public_id)
    {
        $user = User::where('public_id', $public_id)
            ->with(['tracks', 'albums', 'playlists'])
            ->firstOrFail();

        return response()->json($user);
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

    public function showPublic($public_id)
    {
        $user = User::where('public_id', $public_id)->firstOrFail();
         if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
        }
        // return response()->json($user);

        return response()->json([
        'id' => $user->id,
        'username' => $user->username,
        'email' => $user->email,
        'profile_image' => $user->profile_image,
        'bio' => $user->bio,
        ]);
    }


   public function artists()
    {
        $artists = User::where('role', 'artist')
            ->select('id', 'public_id', 'username', 'profile_image', 'bio')
            ->get();
        // $artists = User::where('role', 'artist')->get();

        return response()->json($artists);
    }


    public function showByPublicId($public_id)
    {
        $user = User::where('public_id', $public_id)->with(['tracks', 'albums', 'playlists', ])->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }


    

    
}
