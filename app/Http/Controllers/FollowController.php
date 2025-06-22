<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FollowController extends Controller
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

    public function toggleFollow(Request $request, $artistId)
    {
        $user = auth()->user();

        $artist = User::where('id', $artistId)->where('role', 'artist')->firstOrFail();

        $alreadyFollowing = DB::table('follows')
            ->where('follower_id', $user->id)
            ->where('followed_id', $artist->id)
            ->exists();

        if ($alreadyFollowing) {
            DB::table('follows')
                ->where('follower_id', $user->id)
                ->where('followed_id', $artist->id)
                ->delete();

            return response()->json(['followed' => false]);
        } else {
            DB::table('follows')->insert([
                'follower_id' => $user->id,
                'followed_id' => $artist->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['followed' => true]);
        }
    }

    public function followedArtists()
    {
        $user = auth()->user();

        $artists = $user->following()->where('role', 'artist')->get(['id', 'username', 'profile_image', 'public_id']);

        return response()->json($artists);
    }

    public function isFollowing($artistId)
    {
        $user = auth()->user();

        $isFollowing = DB::table('follows')
            ->where('follower_id', $user->id)
            ->where('followed_id', $artistId)
            ->exists();

        return response()->json(['is_following' => $isFollowing]);
    }
    
}
