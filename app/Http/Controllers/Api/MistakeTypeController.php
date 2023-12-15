<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MistakeTypeController\StoreMistakeTypeRequest;
use App\Http\Requests\Api\MistakeTypeController\UpdateMistakeTypeRequest;
use App\Http\Resources\MistakeType\MistakeTypeResource;
use App\Models\MistakeType;
use Illuminate\Http\Request;

class MistakeTypeController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Menampilkan daftar jenis kesalahan',
            'data' => MistakeTypeResource::collection(MistakeType::all())
        ]);
    }

    public function store(StoreMistakeTypeRequest $request)
    {
        $mistakeType = MistakeType::create($request->except(['active']));
        return response()->json([
            'message' => 'Berhasil membuat jenis kesalahan baru',
            'data' => new MistakeTypeResource($mistakeType->fresh())
        ], 201);
    }

    public function show(MistakeType $mistakeType)
    {
        return response()->json([
            'message' => 'Menampilkan detail jenis kesalahan',
            'data' => new MistakeTypeResource($mistakeType)
        ]);
    }

    public function update(UpdateMistakeTypeRequest $request, MistakeType $mistakeType)
    {
        $mistakeType->update($request->all());
        return response()->json([
            'message' => 'Berhasil mengupdate jenis kesalahan',
            'data' => new MistakeTypeResource($mistakeType->fresh())
        ]);
    }

    public function destroy(MistakeType $mistakeType)
    {
        $mistakeType->delete();
        return response()->json([
            'message' => 'Berhasil menghapus jenis kesalahan'
        ]);
    }
}
