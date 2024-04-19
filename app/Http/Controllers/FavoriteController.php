<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()->favorites;
        return FavoriteResource::collection($favorites);
    }
}
