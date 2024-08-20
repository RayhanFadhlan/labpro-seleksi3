<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name'];
    protected $keyType = 'string';
    public function films()
    {
        return $this->belongsToMany(Film::class);
    }
}
