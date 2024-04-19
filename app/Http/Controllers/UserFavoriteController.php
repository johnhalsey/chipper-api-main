<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\CreateFavoriteRequest;

class UserFavoriteController extends Controller
{
    public function store(CreateFavoriteRequest $request, User $user)
    {
        $request->user()->favorites()->create([
            'favoriteable_id' => $user->id,
            'favoriteable_type' => User::class,
        ]);

        return response()->noContent(Response::HTTP_CREATED);
    }

    public function destroy(Request $request, User $user)
    {
        $favourite = $request->user()->favorites()
            ->where('favoriteable_id', $user->id)
            ->where('favoriteable_type', User::class)
            ->firstOrFail();

        $favourite->delete();

        return response()->noContent();
    }
}
