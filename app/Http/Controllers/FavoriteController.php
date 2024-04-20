<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\FavoriteResource;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites['posts'] = Post::find($request->user()->favorites()->where('favoriteable_type', Post::class)->get()->pluck('favoriteable_id')->toArray());
        $favorites['users'] = User::find($request->user()->favorites()->where('favoriteable_type', User::class)->get()->pluck('favoriteable_id')->toArray());
        return new FavoriteResource($favorites);
    }
}
