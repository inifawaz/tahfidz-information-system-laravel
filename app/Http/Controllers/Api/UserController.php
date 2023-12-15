<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserController\StoreUserRequest;
use App\Http\Requests\Api\UserController\UpdateMeUserRequest;
use App\Http\Requests\Api\UserController\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $usersQuery = User::query();
        $message = 'Menampilkan daftar user';

        if ($request->active === 'true') {
            $usersQuery->where('active', true);
            $message = 'Menampilkan daftar user yang aktif';
        }
        if ($request->active === 'false') {
            $usersQuery->where('active', false);
            $message = 'Menampilkan daftar user yang tidak aktif';
        }

        $users = $usersQuery->get();

        return response()->json([
            'message' => $message,
            'data' => UserResource::collection($users)
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->except('active'));
        $user->assignRole($request->roles);
        return response()->json([
            'message' => 'Berhasil membuat user baru',
            'data' => new UserResource($user->fresh())
        ], 201);
    }



    public function show(User $user)
    {
        return response()->json([
            'message' => 'Menampilkan detail user',
            'data' => new UserResource($user)
        ]);
    }



    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->syncRoles($request->roles);
        return response()->json([
            'message' => 'Berhasil mengupdate user',
            'data' => new UserResource($user->fresh())
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'Berhasil menghapus user'
        ]);
    }

    public function showMe()
    {
        $user = Auth::user();
        return response()->json([
            'message' => 'Menampilkan detail user saat ini',
            'data' => new UserResource($user)
        ]);
    }

    public function updateMe(UpdateMeUserRequest $request, User $user)
    {
        $user = User::find(Auth::user()->id);
        $user->update($request->except('active'));
        return response()->json([
            'message' => 'Berhasil mengupdate user saat ini',
            'data' => new UserResource($user->fresh())
        ]);
    }
}
