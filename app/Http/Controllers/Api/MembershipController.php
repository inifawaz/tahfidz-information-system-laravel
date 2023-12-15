<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MembershipController\DestroyMembershipRequest;
use App\Http\Requests\Api\MembershipController\StoreMembershipRequest;
use App\Http\Resources\Group\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function store(StoreMembershipRequest $request)
    {
        $group = Group::findOrFail($request->group_id);
        $group->students()->attach($request->student_ids);
        return response()->json([
            'message' => "Berhasil menambahkan murid pada group nomer {$group->number} periode {$group->period->name} level {$group->level->name}",
            'data' => new GroupResource($group->fresh(['students']))
        ], 201);
    }

    public function destroy(DestroyMembershipRequest $request)
    {
        $group = Group::findOrFail($request->group_id);
        $group->students()->detach($request->student_ids);
        return response()->json([
            'message' => "Berhasil menghapus murid pada group nomer {$group->number} periode {$group->period->name} level {$group->level->name}",
            'data' => new GroupResource($group->fresh(['students']))
        ]);
    }
}
