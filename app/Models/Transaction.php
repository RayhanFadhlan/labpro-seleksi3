<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['user_id', 'film_id'];

    protected $keyType = 'string';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function film(){
        return $this->belongsTo(Film::class);
    }

}
