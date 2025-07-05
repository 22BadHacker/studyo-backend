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
        // $user = User::where('public_id', $public_id)->firstOrFail();

        $albums = Album::where('$public_id', $public_id)
            ->with('tracks', 'user') 
            ->get()
            ->firstOrFail();

        return response()->json($albums);
    }




    public function myAlbums()
    {
        $user = auth()->user();

        $albums = Album::where('user_id', $user->id)->get();

        return response()->json($albums);
    }




    public function showByPublicId($public_id)
    {
        $album = Album::with(['user', 'tracks'])->where('public_id', $public_id)->firstOrFail();

        $moreAlbums = Album::where('user_id', $album->user_id)
            ->where('id', '!=', $album->id)
            ->take(6)
            ->get();

        return response()->json([
            'album' => $album,
            'more_albums' => $moreAlbums,
        ]);
    }
//    public function latest()
//     {
//         $albums = Album::with('user')
//             ->orderBy('created_at')
//             ->take(10)
//             ->get();

//         return response()->json($albums);
//     }

    public function allAlbums()
    {
        $albums = Album::with(['user', 'tracks'])->take(8)->latest()->get();

        return response()->json($albums);
    }

    public function selectedAlbums(Request $request)
    {
        $request->validate([
            'album_ids' => 'required|array',
            'album_ids.*' => 'integer|exists:albums,id'
        ]);

        $albums = Album::with(['user', 'tracks'])
                    ->whereIn('id', $request->album_ids)
                    ->get();

        return response()->json($albums);
    }


}
