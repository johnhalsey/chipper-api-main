<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface CanBeFavorited
{
    public function favoritables(): MorphMany;
}
