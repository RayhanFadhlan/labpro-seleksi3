<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Film extends Model
{
    use HasFactory,HasUuids;
    protected $fillable = [
        'title',
        'description',
        'director',
        'release_year',
        'price',
        'duration',
        'cover_image_url',
        'video_url'
    ];
    protected $keyType = 'string';
    public function genres() {
        return $this->belongsToMany(Genre::class);
    }
}
