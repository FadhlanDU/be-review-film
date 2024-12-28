<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Casts extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'casts';

    protected $fillable = ['name', 'age', 'bio'];

    public function listmovies() {
        return $this->belongsToMany(Movie::class, 'cast__movies', 'cast_id', 'movie_id');
    }
    
}
