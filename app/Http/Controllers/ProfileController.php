<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'username' => 'string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_image', 'public');
            $data['profile_image'] = '/storage/' . $path;
        }

        $user->update($data);

        return response()->json($user);
    }


    // public function updateImage(Request $request)
    //     {
    //         $user = $request->user();

    //         if ($request->hasFile('image')) {
    //             $request->validate([
    //                 'image' => 'image|max:2048',
    //             ]);

    //             $path = $request->file('image')->store('profile_images', 'public');
    //             $user->profile_image = '/storage/' . $path;
    //             $user->save();

    //             return response()->json(['message' => 'Profile image updated', 'profile_image' => $user->profile_image]);
    //         }

    //         return response()->json(['message' => 'No image uploaded'], 422);
    //     }

}
