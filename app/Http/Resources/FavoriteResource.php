<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FavoriteResource extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'posts' => PostResource::collection($this->resource['posts']),
            'users' => UserResource::collection($this->resource['users']),
        ];
    }
}
