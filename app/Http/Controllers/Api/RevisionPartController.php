<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RevisionPartController\StoreRevisionPartRequest;
use App\Http\Resources\RevisionPart\RevisionPartResource;
use App\Models\Level;
use App\Models\Part;
use App\Models\Period;
use App\Models\PromotionPart;
use App\Models\Quarter;
use App\Models\RevisionPart;
use App\Models\RevisionTask;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevisionPartController extends Controller
{
    public function index(Request $request)
    {
        $query = RevisionPart::query();
        $message = 'Menampilkan daftar murojaah';

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

        $revisionParts = $query->get();

        return response()->json([
            'message' => $message,
            'data' => RevisionPartResource::collection($revisionParts)
        ]);
    }
    public function store(StoreRevisionPartRequest $request)
    {
        $revisionTaskType = Level::find($request->level_id)->revision_task_type;
        $revisionQuarterPortion = Level::find($request->level_id)->revision_quarter_portion;
        $quarterIds = Quarter::where('part_id', $request->part_id)->pluck('id');
        $promotionPartsCompleted = PromotionPart::where('student_id', $request->student_id)->where('completed', true)->get()->sortBy('part.number')->pluck('part.id') ?? null;
        $lastRevisionPartCompleted = RevisionPart::where('student_id', $request->student_id)->where('completed', true)->latest()->first()->part->id ?? null;
        if (!$lastRevisionPartCompleted) {
            $newRevisionPart = $promotionPartsCompleted->first();
        } else {
            $newRevisionPart = null;
            $totalPromotionPartsCompleted = count($promotionPartsCompleted);
            $indexLastRevisionPart = $promotionPartsCompleted->search($lastRevisionPartCompleted);
            if ($indexLastRevisionPart < $totalPromotionPartsCompleted - 1) {
                $newRevisionPart = $promotionPartsCompleted->slice($indexLastRevisionPart + 1, 1)->first();
            } else {
                $newRevisionPart = $promotionPartsCompleted->first();
            }
        }

        try {
            DB::beginTransaction();
            $revisionPart = RevisionPart::create([
                'part_id' => $newRevisionPart,
                'student_id' => $request->student_id,
                'period_id' => $request->period_id,
                'level_id' => $request->level_id
            ]);

            foreach ($quarterIds->chunk($revisionQuarterPortion) as $item) {

                $revisionTaskType = null;
                if (Level::find($request->level_id)->revision_task_type === 'setoran sempurna') {
                    $revisionTaskType = 'setoran sempurna';
                } else if (Level::find($request->level_id)->revision_task_type === 'pertanyaan') {
                    $revisionTaskType = 'pertanyaan';
                } else if (Level::find($request->level_id)->revision_task_type === 'acak') {
                    $revisionTaskType = fake()->randomElement(['setoran sempurna', 'pertanyaan']);
                }
                $revisionTask = RevisionTask::create([
                    'revision_part_id' => $revisionPart->id,
                    'type' => $revisionTaskType,
                ]);

                $revisionTask->quarters()->attach($item);
            }

            DB::commit();

            return response()->json([
                'message' => "Berhasil membuat murojaah juz $newRevisionPart",
                'data' => new RevisionPartResource($revisionPart->fresh(['tasks.quarters']))
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    public function show(RevisionPart $revisionPart)
    {
        return response()->json([
            'data' => new RevisionPartResource($revisionPart->load(['tasks.quarters']))
        ]);
    }

    public function destroy(RevisionPart $revisionPart)
    {
        $revisionPart->delete();
        return response()->json([
            'message' => 'Berhasil mengahapus murojaah'
        ]);
    }
}
