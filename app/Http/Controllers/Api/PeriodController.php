<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PeriodController\StorePeriodRequest;
use App\Http\Requests\Api\PeriodController\UpdateActivePeriodRequest;
use App\Http\Requests\Api\PeriodController\UpdatePeriodRequest;
use App\Http\Resources\Period\PeriodResource;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index(Request $request)
    {
        $periodsQuery = Period::query();
        $message = 'Menampilkan daftar periode';
        if ($request->active === 'true') {
            $periodsQuery->where('active', true);
            $message = 'Menampilkan daftar periode yang aktif';
        }
        if ($request->active === 'false') {
            $periodsQuery->where('active', false);
            $message = 'Menampilkan daftar periode yang tidak aktif';
        }
        $periods = $periodsQuery->oldest()->get();
        return response()->json([
            'message' => $message,
            'data' => PeriodResource::collection($periods)
        ]);
    }


    public function store(StorePeriodRequest $request)
    {
        $period = Period::create($request->except(['active']));
        return response()->json([
            'message' => 'Berhasil membuat periode baru',
            'data' => new PeriodResource($period->fresh())
        ], 201);
    }

    public function show(Period $period)
    {
        return response()->json([
            'message' => 'Menampilkan detail periode',
            'data' => new PeriodResource($period)
        ]);
    }

    public function update(UpdatePeriodRequest $request, Period $period)
    {
        $period->update($request->all());
        return response()->json([
            'message' => 'Berhasil mengupdate periode',
            'data' => new PeriodResource($period->fresh())
        ]);
    }

    public function destroy(Period $period)
    {
        $period->delete();
        return response()->json([
            'message' => 'Berhasil menghapus periode'
        ]);
    }

    public function showActive()
    {
        $period = Period::where('active', true)->firstOrFail();
        return response()->json([
            'message' => 'Menampilkan detail periode yang aktif',
            'data' => new PeriodResource($period)
        ]);
    }

    public function updateActive(UpdateActivePeriodRequest $request)
    {
        $activePeriod = Period::where('active', true)->firstOrFail();
        $activePeriod->update($request->all());
        return response()->json([
            'message' => 'Berhasil mengupdate perioded yang aktif',
            'data' => new PeriodResource($activePeriod->fresh())
        ]);
    }

    public function destroyActive()
    {
        $activePeriod = Period::where('active', true)->firstOrFail();
        $activePeriod->delete();
        return response()->json([
            'message' => 'Berhasil menghapus periode yang aktif',
        ]);
    }
}
