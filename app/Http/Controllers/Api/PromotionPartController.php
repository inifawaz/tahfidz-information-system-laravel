<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PromotionPartController\StorePromotionPartRequest;
use App\Http\Resources\PromotionPart\PromotionPartResource;
use App\Models\Level;
use App\Models\Part;
use App\Models\Period;
use App\Models\PromotionPart;
use App\Models\PromotionTask;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromotionPartController extends Controller
{
    public function index(Request $request)
    {
        $query = PromotionPart::query();
        $message = 'Menampilkan daftar ujian kenaikan juz';

        if (is_numeric($request->part)) {
            $part = Part::findOrFail($request->part);
            $query->where('part_id', $request->part);
            $message .= " {$part->number}";
        }

        if (is_numeric($request->student)) {
            $student = Student::findOrFail($request->student);
            $query->where('student_id', $request->student);
            $message .= ", murid {$student->name},";
        }

        if (is_numeric($request->period)) {
            $period = Period::findOrFail($request->period);
            $query->where('period_id', $request->period);
            $message .= " periode {$period->name},";
        }

        if (is_numeric($request->level)) {
            $level = Level::findOrFail($request->level);
            $query->where('level_id', $request->level);
            $message .= " level {$level->name},";
        }

        if ($request->completed === 'true') {
            $query->where('completed', true);
            $message .= " yang berhasil diselesaikan";
        }

        if ($request->completed === 'false') {
            $query->where('completed', false);
            $message .= " yang belum berhasil diselesaikan";
        }

        $promotionParts = $query->get();

        return response()->json([
            'message' => $message,
            'data' => PromotionPartResource::collection($promotionParts)
        ]);
    }


    public function store(StorePromotionPartRequest $request)
    {
        try {
            DB::beginTransaction();
            $promotionPart = PromotionPart::create($request->all());
            $promotionRecitationTask = PromotionTask::create([
                'promotion_part_id' => $promotionPart->id,
                'type' => 'setoran sempurna',
                'due_date' => $request->due_date ?? now()->addDays(2)->toDateString()
            ]);
            $promotionQuestionTask = PromotionTask::create([
                'promotion_part_id' => $promotionPart->id,
                'type' => 'pertanyaan',
                'due_date' => $request->due_date ?? now()->addDays(2)->toDateString()
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Berhasil membuat ujian kenaikan juz ' .  $promotionPart->part->number,
                'data' => new PromotionPartResource($promotionPart->fresh(['tasks']))
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    public function show(PromotionPart $promotionPart)
    {
        return response()->json([
            'message' => 'Menampilkan detail ujian kenaikan juz',
            'data' => new PromotionPartResource($promotionPart->fresh(['tasks.submissions.mistakes']))
        ]);
    }

    public function destroy(PromotionPart $promotionPart)
    {
        $promotionPart->delete();
        return response()->json([
            'message' => 'Berhasil menghapus ujian kenaikan juz'
        ]);
    }
}
