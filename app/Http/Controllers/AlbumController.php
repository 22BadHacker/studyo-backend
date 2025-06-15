<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AlbumController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Album::with(['user', 'genre'])->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image',
            'genre_id' => 'required|exists:genres,id',
            'release_date'=> 'required|date',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('albums', 'public');
        }

        $validated['user_id'] = auth()->id();

        $album = Album::create($validated);

        return response()->json($album, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
       return $album->load(['user', 'tracks', 'genre']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Album $album)
    {
       $this->authorize('update', $album);

        $data = $request->only(['title', 'description', 'genre_id']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('albums', 'public');
        }

        $album->update($data);

        return response()->json($album);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        $this->authorize('delete', $album);

        $album->delete();

        return response()->json(null, 204);
    }



    public function getByPublicId($public_id)
    {
        $user = User::where('public_id', $public_id)->firstOrFail();

        $albums = Album::where('user_id', $user->id)
            ->with('genre') 
            ->get();

        return response()->json($albums);
    }




    public function myAlbums()
    {
        $user = auth()->user();

        // Return albums where the artist is the current user
        $albums = Album::where('user_id', $user->id)->get();

        return response()->json($albums);
    }




    public function showByPublicId($public_id)
    {
        $album = Album::where('public_id', $public_id)
            ->with('tracks', 'user') // assuming a hasMany relationship
            ->firstOrFail();

        return response()->json($album);
    }

}
