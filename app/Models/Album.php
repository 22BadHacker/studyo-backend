<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'user_id',
        'genre_id',
        'release_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            $album->public_id = Str::random(22); // 22 Example: user_6f3a9b2c1a2b
            // $user->public_id = 'user_' . Str::random(22); // 22 Example: user_6f3a9b2c1a2b
        });
    }
}
