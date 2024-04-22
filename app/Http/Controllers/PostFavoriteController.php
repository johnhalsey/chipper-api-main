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
        $request->user()->saveFavorite($post);

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, Post $post)
    {
        $request->user()->removeFavorite($post);

        return response()->noContent();
    }
}
