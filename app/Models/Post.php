<?php

namespace App\Models;

use App\Contracts\CanBeFavorited;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model implements CanBeFavorited
{
    use HasFactory, CanBeFavorite;

    protected $fillable = ['title', 'body', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
