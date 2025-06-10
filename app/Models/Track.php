<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
     use HasFactory;

    protected $fillable = [
        'title',
        'genre',
        'file_path',
        'cover_image',
        'duration',
        'user_id',
        'album_id',
    ];

    // Relationship: A track belongs to a user (artist)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    // Relationship: A track belongs to an Album (artist)
    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)->withTimestamps();
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'library_tracks')->withTimestamps();
    }


    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
