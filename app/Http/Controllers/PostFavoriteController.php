<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\CreateFavoriteRequest;
use Illuminate\Http\Response;

/**
 * @group Favorites
 *
 * API endpoints for managing favorites
 */
class PostFavoriteController extends Controller
{
    public function store(CreateFavoriteRequest $request, Post $post)
    {
        $request->user()->favorites()->create([
            'favoriteable_id' => $post->id,
            'favoriteable_type' => Post::class,
        ]);

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, Post $post)
    {
        $favourite = $request->user()->favorites()
            ->where('favoriteable_id', $post->id)
            ->where('favoriteable_type', Post::class)
            ->firstOrFail();

        $favourite->delete();

        return response()->noContent();
    }
}
