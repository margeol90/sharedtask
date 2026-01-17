<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $account = Account::create([
                'name' => "{$user->name}'s Account",
            ]);

            $user->accounts()->attach($account->id);
            $user->last_active_account_id = $account->id;
            $user->save();

            return $user;
        });

        $user->load(['accounts', 'activeAccount']);

        // CREATE TOKEN
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token, // return the token
        ]);
    }
}
