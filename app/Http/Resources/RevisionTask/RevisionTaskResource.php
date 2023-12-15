<?php

namespace App\Http\Resources\RevisionTask;

use App\Http\Resources\RevisionSubmission\RevisionSubmissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisionTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


        return [
            'id' => $this->id,
            'revision_part_id' => $this->revision_part_id,
            'type' => $this->type,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'quarters' => $this->whenLoaded('quarters'),
            'submissions' => RevisionSubmissionResource::collection($this->whenLoaded('submissions'))
        ];
    }
}
