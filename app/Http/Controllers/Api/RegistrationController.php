<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegistrationController\DestroyRegistrationRequest;
use App\Http\Requests\Api\RegistrationController\StoreRegistrationRequest;
use App\Http\Resources\Registration\RegistrationResource;
use App\Models\Period;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(StoreRegistrationRequest $request)
    {
        $period = Period::findOrFail($request->period_id);
        $period->students()->attach($request->student_ids, [
            'level_id' => $request->level_id
        ]);
        $registrations = Registration::where('period_id', $request->period_id)->where('level_id', $request->level_id)->whereIn('student_id', $request->student_ids)->get();

        return response()->json([
            'message' => 'Berhasil membuat registrasi baru',
            'data' =>  RegistrationResource::collection($registrations)
        ], 201);
    }

    public function destroy(DestroyRegistrationRequest $request)
    {
        $period = Period::findOrFail($request->period_id);
        $period->students()->wherePivot('level_id', $request->level_id)->detach($request->student_ids);
        $registrations = Registration::where('period_id', $request->period_id)->where('level_id', $request->level_id)->get();

        return response()->json([
            'message' => 'Berhasil menghapus registrasi',
            'data' =>  RegistrationResource::collection($registrations)

        ]);
    }
}
