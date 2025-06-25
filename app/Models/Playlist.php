<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = ['name','cover_image', 'user_id', 'description', 'is_public'];

    // protected $appends = ['cover_url'];

    // public function getCoverUrlAttribute()
    // {
    //     return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'playlist_track')->withTimestamps();
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'library_playlists')->withTimestamps();
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($playlist) {
            $playlist->public_id = Str::random(22); 
        });
    }

    public function previewTracks()
    {
        return $this->belongsToMany(Track::class)
            ->select('id', 'title', 'artist_id', 'duration')
            ->limit(3);
    }


}
