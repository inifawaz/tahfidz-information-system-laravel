<?php

namespace App\Http\Resources\RevisionMistake;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RevisionMistakeResource extends JsonResource
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
            'revision_submission_id' => $this->revision_submission_id,
            'mistake_type_id' => $this->mistake_type_id,
            'mistake_type_name' => $this->mistakeType->name,
            'verse_id' => $this->verse_id,
            'from_index' => $this->from_index,
            'to_index' => $this->to_index,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
