<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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


    public function edit(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        
        $user = auth()->user(); // Must be logged in via Sanctum
        //  $user = $request->user(); // Must be logged in via Sanctum
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if($user->profile_image) {
                Storage::disk("public")->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = '/storage/' . $path;
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
       
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


    public function resolveProfile($public_id)
    {
        $user = User::where('public_id', $public_id)->firstOrFail();
        
        return response()->json([
            'redirect_to' => $user->role === 'artist' ? "/artist/$public_id" : "/users/$public_id"
        ]);
    }


    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'username' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        if ($request->username) {
            $user->username = $request->username;
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
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
