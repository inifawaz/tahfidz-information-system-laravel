<?php

namespace App\Http\Resources\PromotionSubmission;

use App\Http\Resources\PromotionMistake\PromotionMistakeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionSubmissionResource extends JsonResource
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
            'promotion_task_id' => $this->promotion_task_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'duration' => $this->duration,
            'success' => $this->success,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'mistakes' => PromotionMistakeResource::collection($this->whenLoaded('mistakes'))
        ];
    }
}
