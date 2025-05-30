<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

   public function follow($id)
    {
        $user = User::findOrFail($id);
        Auth::user()->following()->attach($user->id);
        return response()->json(['message' => 'Followed']);
    }

    public function unfollow($id)
    {
        $user = User::findOrFail($id);
        Auth::user()->following()->detach($user->id);
        return response()->json(['message' => 'Unfollowed']);
    }

    public function check($id)
    {
        $user = User::findOrFail($id);
        $isFollowing = Auth::user()->following->contains($user->id);
        return response()->json(['isFollowing' => $isFollowing]);
    }
}
