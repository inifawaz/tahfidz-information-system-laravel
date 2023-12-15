<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GroupController\StoreGroupRequest;
use App\Http\Requests\Api\GroupController\UpdateGroupRequest;
use App\Http\Resources\Group\GroupResource;
use App\Models\Group;
use App\Models\Level;
use App\Models\Period;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::orderBy('number');
        $message = 'Menampilkan daftar group';

        if ($request->period === 'active' && is_numeric($request->level)) {
            $period = Period::where('active', true)->firstOrFail();
            $level = Level::findOrFail($request->level);
            $groups = Group::with('students')->where('period_id', $period->id)->where('level_id', $level->id)->orderBy('number');
            $message = "Menampilkan daftar group periode {$period->name} level {$level->name}";
        }

        if (is_numeric($request->period) && is_numeric($request->level)) {
            $period = Period::findOrFail($request->period);
            $level = Level::findOrFail($request->level);
            $groups = Group::with('students')->where('period_id', $period->id)->where('level_id', $level->id)->orderBy('number');
            $message = "Menampilkan daftar group periode: {$period->name} level {$level->name}";
        }

        return response()->json([
            'message' => $message,
            'data' => GroupResource::collection($groups->get())
        ]);
    }

    public function store(StoreGroupRequest $request)
    {
        $group = Group::create($request->all());
        $group->students()->attach($request->student_ids);

        return response()->json([
            'message' => 'Berhasil menyimpan group baru',
            'data' => new GroupResource($group->fresh('students'))
        ], 201);
    }

    public function show(Group $group)
    {
        return response()->json([
            'message' => 'Menampilkan detail group',
            'data' => new GroupResource($group->load('students'))
        ]);
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        $group->update($request->only(['user_id', 'number']));
        return response()->json([
            'message' => 'Berhasil mengupdate group',
            'data' => new GroupResource($group->fresh())
        ]);
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return response()->json([
            'message' => 'Berhasil menghapus group'
        ]);
    }
}
