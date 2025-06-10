<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Track::with('genre', 'user', 'album')->get());
        // return Track::with(['user', 'album'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'title' => 'required|string',
        //     'artist' => 'required|string',
        //     'file_path' => 'required|string',
        //     'user_id' => 'required|exists:users,id',
        //     'genre_id' => 'nullable|exists:genres,id',
        // ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:mp3,wav,ogg',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'genre_id' => 'nullable|exists:genres,id'
        ]);

         if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $musicPath = $request->file('file_path')->store('tracks', 'public');

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('track_covers', 'public');
        }

        $track = Track::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
            'genre_id' => $request->genre_id,
            'file_path' => $musicPath,
            'cover_image' => $coverPath,
        ]);

        return response()->json($track, 201);

       
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $track = Track::with('genre', 'user')->findOrFail($id);
        return response()->json($track);
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
        $track = Track::findOrFail($id);

        // Delete files if they exist
        if ($track->file_path) {
            Storage::disk('public')->delete($track->file_path);
        }

        if ($track->cover_image) {
            Storage::disk('public')->delete($track->cover_image);
        }

        $track->delete();
        return response()->json(['message' => 'Track deleted successfully']);
    }
}
