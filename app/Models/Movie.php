<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Movie extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'movies';

    protected $fillable = ['title', 'summary', 'poster', 'genre_id', 'year'];

    public function genre() {
        return $this->belongsTo(Genre::class, 'genre_id');
    }

    public function listreview() {
        return $this->hasMany(Reviews::class, 'movie_id');
    }

    public function listcasts() {
        return $this->BelongsToMany(Casts::class, 'cast__movies', 'movie_id', 'cast_id');
    }
}
