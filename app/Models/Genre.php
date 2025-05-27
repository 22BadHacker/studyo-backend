<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];


    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
