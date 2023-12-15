<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RevisionSubmissionController\StoreCurrentRevisionSubmissionRequest;
use App\Http\Resources\RevisionSubmission\RevisionSubmissionResource;
use App\Models\RevisionMistake;
use App\Models\RevisionPart;
use App\Models\RevisionSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevisionSubmissionController extends Controller
{
    public function storeCurrent(StoreCurrentRevisionSubmissionRequest $request, RevisionPart $revisionPart)
    {
        $currentRevisionTask = $revisionPart->tasks()->where('completed', false)->orderBy('id')->first();

        try {
            DB::beginTransaction();
            if ($currentRevisionTask->type === 'setoran sempurna') {
                $maxMistake = $revisionPart->level->max_revision_recitation_mistake;
                $success = count($request->mistakes ?? []) > $maxMistake ? false : true;
            }
            if ($currentRevisionTask->type === 'pertanyaan') {
                $maxMistake = $revisionPart->level->max_revision_question_mistake;
                $success = count($request->mistakes ?? []) > $maxMistake ? false : true;
            }

            $revisionSubmission = RevisionSubmission::create([
                'revision_task_id' => $currentRevisionTask->id,
                'user_id' => Auth::user()->id,
                'duration' => $request->duration,
                'success' => $success
            ]);

            if ($success) {
                $currentRevisionTask->update([
                    'completed' => true
                ]);

                if ($currentRevisionTask->type === 'pertanyaan') {
                    $revisionPart->update([
                        'completed' => true
                    ]);
                }
            }



            if (isset($request->mistakes)) {
                foreach ($request->mistakes as $mistake) {
                    $mistake['revision_submission_id'] = $revisionSubmission->id;
                    RevisionMistake::create([
                        'revision_submission_id' => $mistake['revision_submission_id'],
                        'mistake_type_id' => $mistake['mistake_type_id'] ?? null,
                        'verse_id' => $mistake['verse_id'] ?? null,
                        'from_index' => $mistake['from_index'] ?? null,
                        'to_index' => $mistake['to_index'] ?? null
                    ]);
                }
            }


            DB::commit();
            return response()->json([
                'message' => 'Berhasil menyimpam setoran ujian kenaikan juz ' . $revisionPart->part->number . " ($currentRevisionTask->type)",
                'data' => new RevisionSubmissionResource($revisionSubmission->fresh(['mistakes']))
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }
}
