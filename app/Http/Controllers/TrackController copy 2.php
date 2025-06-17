<?php

namespace App\Http\Controllers;

use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use getID3;

class TrackController extends Controller
{

    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index($public_id)
    {
        $user = User::where('public_id', $public_id)->firstOrFail();
        $track = track::where('user_id', $user->id)
            ->with('genre', 'album') 
            ->latest()
            ->get();

        return response()->json($track);

        
    //    return  Track::with(['album', 'genre', 'user'])->latest()->get();
    }

    public function preview()
    {
        return auth()->user()
            ->tracks()
            ->with('album', 'genre')
            ->latest()
            ->take(4)
            ->get();
    }

    // Full list (already exists)
    public function myTracks()
    {
        return auth()->user()
            ->tracks()
            ->with('album', 'genre')
            ->latest()
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     $request->validate([
            'title' => 'required|string|max:255',
            'file_path' => 'required|file|mimes:mp3,wav,aac|max:20480',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg, webp|max:5120',
            // 'duration' => 'required|integer',
            'album_id' => 'nullable|exists:albums,id',
            'genre_id' => 'nullable|exists:genres,id',
            'release_date' => 'nullable|date',
        ]);

        // $validated['user_id'] = auth()->id();

        $audioFile = $request->file('file_path');
        $audioPath = $audioFile->store('tracks/audio', 'public');


        $getID3 = new \getID3;
        $fileInfo = $getID3->analyze($audioFile->getRealPath());


        $duration = isset($fileInfo['playtime_string']) ? $fileInfo['playtime_string'] : null;




        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
           $coverPath = $coverImage->store('track/covers', 'public');
        }



         $track = new Track();
        $track->title = $request->title;
        $track->file_path = $audioPath;
        $track->cover_image = $coverPath;
        $track->album_id = $request->album_id;
        $track->genre_id = $request->genre_id;
        $track->release_date = $request->release_date;
        $track->duration = $duration; // e.g., "3:47"
        $track->user_id = auth()->id(); // make sure user is authenticated
        $track->save();

        // $track = Track::create($validated);

         return response()->json([
        'message' => 'Track uploaded successfully!',
        'track' => $track,
        ], 201);

       
    }

    /**
     * Display the specified resource.
     */
    public function show(Track $track)
    {
        return $track->load(['album', 'genre', 'user']);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, Track $track)
    {
        $this->authorize('update', $track);

        $data = $request->only(['title', 'duration', 'album_id', 'genre_id']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('track_covers', 'public');
        }

        $track->update($data);

        return response()->json($track);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track)
    {
       $this->authorize('delete', $track);

        $track->delete();

        return response()->json(null, 204);
    }




    public function getByUser($public_id)
    {
        $user = User::where('public_id', $public_id)->firstOrFail();
        return Track::where('user_id', $user->id)
            ->with('album', 'genre', 'user')
            ->latest()
            // ->take(4) // limit to 4 for preview
            ->get();
    }



    public function togglePopular($id)
    {
        

        $track = Track::where('id', $id)->where('user_id', auth()->id())->first();

        if (!$track) {
            return response()->json(['message' => 'Track not found or unauthorized'], 404);
        }

        if (!$track->is_popular) {
            $popularCount = Track::where('user_id', auth()->id())->where('is_popular', true)->count();
            if ($popularCount >= 10) {
                return response()->json(['message' => 'You can only mark up to 10 tracks as hot'], 403);
            }
        }

        $track->is_popular = !$track->is_popular;
        $track->save();

        return response()->json([
            'message' => 'Track popularity updated',
            'is_popular' => $track->is_popular
        ]);
    }
}
