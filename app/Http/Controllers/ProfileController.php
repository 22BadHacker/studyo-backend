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
            // 'email' => 'nullable|string',
            'profile_image' => 'nullable|image | mimes:jpeg,png,jpg,gif,webp, avif, svg|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_image', 'public');
            $data['profile_image'] = '/storage/' . $path;
        }

        $user->update($data);

        return response()->json($user);
    }


    public function removeImage(Request $request)
    {
        $user = $request->user();

        if ($user->profile_image && Storage::disk('public')->exists(str_replace('/storage/', '', $user->profile_image))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile_image));
        }

        $user->profile_image = null;
        $user->save();

        return response()->json(['message' => 'Profile image removed.']);
    }

}
