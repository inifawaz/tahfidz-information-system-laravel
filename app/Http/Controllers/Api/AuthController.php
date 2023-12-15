<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthController\StoreAuthRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(StoreAuthRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            throw new AuthenticationException();
        }

        /** @var App/Models/User $user */
        $user = Auth::user();
        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Berhasil login',
            'data' => new UserResource($user),
            'token' => $token
        ]);
    }

    public function destroy()
    {
        /** @var App/Models/User $user */
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'message' => 'Berhasil logout'
        ]);
    }
}
