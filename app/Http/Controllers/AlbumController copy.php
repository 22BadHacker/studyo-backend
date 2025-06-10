<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return Album::with('user')->get();

        $user_id = auth()->user()->id;
        $albums = Album::where('user_id', $user_id)->get();

        return response()->json([
            'albums' => $albums,
            'status' => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->validate([
            'title' => 'required|string',
            // 'user_id' => 'required|exists:users,id'
        ]);

        $data['user_id'] = auth()->user()->id;

        if($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('album_covers', 'public');
            // $data['cover_image'] = '/storage/' . $path;
        }

        Album::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Album created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return response()->json([
            'album' => $album,
            'status' => true,
            'message' => 'Album Data Found'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
        $data = $request->validate([
            'title' => 'required',
        ]);

        if($request->hasFile('cover_image')) {
           if($album->cover_image) {
                Storage::disk("public")->delete($album->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('album_covers', 'public');
        }

        $album->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Album updated successfully'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return response()->json([
            'status' => true,
            'message' => 'Album deleted successfully'
        ]);
    }
}
