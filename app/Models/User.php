<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',           // 'user', 'artist', 'admin'
        'profile_image', 
        'bio', 
        'date_of_birth',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->public_id = Str::random(22); // 22 Example: user_6f3a9b2c1a2b
            // $user->public_id = 'user_' . Str::random(22); // 22 Example: user_6f3a9b2c1a2b
        });
    }



    // Tracks
    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function libraryTracks()
    {
        return $this->belongsToMany(Track::class, 'library_tracks')->withTimestamps();
    }

    public function libraryPlaylists()
    {
        return $this->belongsToMany(Playlist::class, 'library_playlists')->withTimestamps();
    }


    // // Users I follow
    // public function followings()
    // {
    //     return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    // }

    // // Users who follow me
    // public function followers()
    // {
    //     return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')->withTimestamps();
    // }

    // public function isFollowing($userId)
    // {
    //     return $this->following()->where('followed_id', $userId)->exists();
    // }

    // public function following()
    // {
    //     return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id');
    // }

    // // Users who follow this user
    // public function followers()
    // {
    //     return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id');
    // }

    public function following() {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function followers() {
        return $this->hasMany(Follow::class, 'followed_id');
    }


    
}
