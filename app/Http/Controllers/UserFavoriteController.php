<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateFavoriteRequest;

class UserFavoriteController extends Controller
{
    public function store(CreateFavoriteRequest $request, User $user)
    {
        $request->user()->saveFavorite($user);

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, User $user)
    {
        $request->user()->removeFavorite($user);

        return response()->noContent();
    }
}
