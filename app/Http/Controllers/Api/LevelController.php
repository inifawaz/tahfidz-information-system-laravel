<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LevelController\StoreLevelRequest;
use App\Http\Requests\Api\LevelController\UpdateLevelRequest;
use App\Http\Resources\Level\LevelResource;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $levelsQuery = Level::query();
        $message = 'Menampilkan daftar level';
        if ($request->active === 'true') {
            $levelsQuery->where('active', true);
            $message = 'Menampilkan daftar level yang aktif';
        }
        if ($request->active === 'false') {
            $levelsQuery->where('active', false);
            $message = 'Menampilkan daftar level yang tidak aktif';
        }
        $Levels = $levelsQuery->orderBy('number')->get();
        return response()->json([
            'message' => $message,
            'data' => LevelResource::collection($Levels)
        ]);
    }

    public function store(StoreLevelRequest $request)
    {
        if ($request->revision_task_type === 'pertanyaan' || $request->revision_task_type === 'acak') {
            switch ($request->revision_quarter_portion) {
                case 1:
                case 2:
                    abort(422, 'Untuk porsi murojaah 1 atau 2 rub hanya bisa membuat kegiatan murojaah dalam bentuk setoran sempurna saja');
                    break;
            }
        }
        $level = Level::create($request->except(['active']));
        return response()->json([
            'message' => 'Berhasil membuat level baru',
            'data' => new LevelResource($level->fresh())
        ], 201);
    }

    public function show(Level $level)
    {
        return response()->json([
            'message' => 'Menampilkan detail level',
            'data' => new LevelResource($level)
        ]);
    }

    public function update(UpdateLevelRequest $request, Level $level)
    {
        $level->update($request->all());
        return response()->json([
            'message' => 'Berhasil memperbarui level',
            'data' => new LevelResource($level->fresh())
        ]);
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return response()->json([
            'message' => 'Berhasil menghapus level'
        ]);
    }
}
