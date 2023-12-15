<?php

namespace App\Http\Requests\Api\PromotionSubmissionController;

use App\Models\PromotionTask;
use Illuminate\Foundation\Http\FormRequest;

class StoreCurrentPromotionSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    private $promotionTask;
    public function rules(): array
    {
        $this->promotionTask = PromotionTask::where('promotion_part_id', $this->promotionPart->id)->where('type', 'setoran sempurna')->where('completed', false)->first()
            ?? PromotionTask::where('promotion_part_id', $this->promotionPart->id)->where('type', 'pertanyaan')->where('completed', false)->first();


        if (
            $this->promotionPart->tasks()->where('type', 'setoran sempurna')->where('completed', true)->exists() &&
            $this->promotionPart->tasks()->where('type', 'pertanyaan')->where('completed', true)->exists()
        ) {
            abort(422, "Ujian kenaikan juz {$this->promotionPart->part->number} berhasil diselesaikan");
        }

        if ($this->promotionTask->completed) {
            abort(422, "Ujian kenaikan juz {$this->promotionPart->part->number} ({$this->promotionTask->type}) berhasil disetorkan");
        }

        if ($this->promotionTask->type === 'pertanyaan') {
            $promotionTaskRecitation = $this->promotionPart->tasks()->where('type', 'setoran sempurna')->first();
            if ($promotionTaskRecitation->completed == false) {
                abort(422, "Harap selesaikan ujian kenaikan juz {$this->promotionPart->part->number} ({$promotionTaskRecitation->type})");
            }
        }

        return [
            'duration' => [
                'required',
                'date_format:H:i:s'
            ],
            'mistakes' => [
                'array'
            ],
            'mistakes.*.mistake_type_id' => [
                'required',
                'exists:mistake_types,id'
            ],
            'mistakes.*.verse_id' => [
                'required',
                'exists:verses,id'
            ],
            'mistakes.*.from_index' => [
                'integer'
            ],
            'mistakes.*.to_index' => [
                'integer'
            ],

        ];
    }
}
