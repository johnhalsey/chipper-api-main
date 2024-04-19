<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanBeFavorite
{
    public function favoritables(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoriteable');
    }
}
