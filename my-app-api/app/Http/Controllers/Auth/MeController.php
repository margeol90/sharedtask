<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class MeController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user()->load(['accounts', 'activeAccount']);

        return response()->json([
            'user' => new UserResource($user)
        ]);
    }
}