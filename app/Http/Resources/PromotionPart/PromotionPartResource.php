<?php

namespace App\Http\Resources\PromotionPart;

use App\Http\Resources\PromotionTask\PromotionTaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromotionPartResource extends JsonResource
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
            'part_id' => $this->part_id,
            'part_number' => $this->part->number,
            'completed' => $this->completed,
            'student_id' => $this->student_id,
            'student_name' => $this->student->name,
            'period_id' => $this->period_id,
            'period_name' => $this->period->name,
            'level_id' => $this->level_id,
            'level_name' => $this->level->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tasks' => PromotionTaskResource::collection($this->whenLoaded('tasks'))
        ];
    }
}
