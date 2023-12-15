<?php

namespace App\Http\Resources\PromotionTask;

use App\Http\Resources\PromotionSubmission\PromotionSubmissionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionTaskResource extends JsonResource
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
            'promotion_part_id' => $this->promotion_part_id,
            'type' => $this->type,
            'due_date' => $this->due_date,
            'completed' => $this->completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'submissions' => PromotionSubmissionResource::collection($this->whenLoaded('submissions'))
        ];
    }
}
