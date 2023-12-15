<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PromotionSubmissionController\StoreCurrentPromotionSubmissionRequest;
use App\Http\Requests\Api\PromotionSubmissionController\StorePromotionSubmissionRequest;
use App\Http\Resources\PromotionSubmission\PromotionSubmissionResource;
use App\Models\PromotionMistake;
use App\Models\PromotionPart;
use App\Models\PromotionSubmission;
use App\Models\PromotionTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PromotionSubmissionController extends Controller
{
    public function storeCurrent(StoreCurrentPromotionSubmissionRequest $request, PromotionPart $promotionPart)
    {
        $promotionTask =
            PromotionTask::where('promotion_part_id', $promotionPart->id)->where('type', 'setoran sempurna')->where('completed', false)->first()
            ?? PromotionTask::where('promotion_part_id', $promotionPart->id)->where('type', 'pertanyaan')->where('completed', false)->first();

        try {
            DB::beginTransaction();
            if ($promotionTask->type === 'setoran sempurna') {
                $maxMistake = $promotionPart->level->max_promotion_recitation_mistake;
                $success = count($request->mistakes ?? []) > $maxMistake ? false : true;
            }
            if ($promotionTask->type === 'pertanyaan') {
                $maxMistake = $promotionPart->level->max_promotion_question_mistake;
                $success = count($request->mistakes ?? []) > $maxMistake ? false : true;
            }

            $promotionSubmission = PromotionSubmission::create([
                'promotion_task_id' => $promotionTask->id,
                'user_id' => Auth::user()->id,
                'duration' => $request->duration,
                'success' => $success
            ]);

            if ($success) {
                $promotionTask->update([
                    'completed' => true
                ]);

                if ($promotionTask->type === 'pertanyaan') {
                    $promotionPart->update([
                        'completed' => true
                    ]);
                }
            }



            if (isset($request->mistakes)) {
                foreach ($request->mistakes as $mistake) {
                    $mistake['promotion_submission_id'] = $promotionSubmission->id;
                    PromotionMistake::create([
                        'promotion_submission_id' => $mistake['promotion_submission_id'],
                        'mistake_type_id' => $mistake['mistake_type_id'] ?? null,
                        'verse_id' => $mistake['verse_id'] ?? null,
                        'from_index' => $mistake['from_index'] ?? null,
                        'to_index' => $mistake['to_index'] ?? null
                    ]);
                }
            }


            DB::commit();
            return response()->json([
                'message' => 'Berhasil menyimpam setoran ujian kenaikan juz ' . $promotionPart->part->number . " ($promotionTask->type)",
                'data' => new PromotionSubmissionResource($promotionSubmission->fresh(['mistakes']))
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }


    public function store(StorePromotionSubmissionRequest $request, PromotionPart $promotionPart, PromotionTask $promotionTask)
    {
        try {
            DB::beginTransaction();
            if ($promotionTask->type === 'setoran sempurna') {
                $maxMistake = $promotionPart->level->max_promotion_recitation_mistake;
                $success = count($request->mistakes ?? []) > $maxMistake ? false : true;
            }
            if ($promotionTask->type === 'pertanyaan') {
                $maxMistake = $promotionPart->level->max_promotion_question_mistake;
                $success = count($request->mistakes ?? []) > $maxMistake ? false : true;
            }

            $promotionSubmission = PromotionSubmission::create([
                'promotion_task_id' => $promotionTask->id,
                'user_id' => Auth::user()->id,
                'duration' => $request->duration,
                'success' => $success
            ]);


            if ($success) {
                $promotionTask->update([
                    'completed' => true
                ]);

                if ($promotionTask->type === 'pertanyaan') {
                    $promotionPart->update([
                        'completed' => true
                    ]);
                }
            }

            if (isset($request->mistakes)) {
                foreach ($request->mistakes as $mistake) {
                    $mistake['promotion_submission_id'] = $promotionSubmission->id;
                    PromotionMistake::create([
                        'promotion_submission_id' => $mistake['promotion_submission_id'],
                        'mistake_type_id' => $mistake['mistake_type_id'] ?? null,
                        'verse_id' => $mistake['verse_id'] ?? null,
                        'from_index' => $mistake['from_index'] ?? null,
                        'to_index' => $mistake['to_index'] ?? null
                    ]);
                }
            }


            DB::commit();
            return response()->json([
                'message' => 'Berhasil menyimpam setoran ujian kenaikan juz ' . $promotionPart->part->number . " ($promotionTask->type)",
                'data' => new PromotionSubmissionResource($promotionSubmission->fresh(['mistakes']))
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }
}
