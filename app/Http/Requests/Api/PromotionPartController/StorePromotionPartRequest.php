<?php

namespace App\Http\Requests\Api\PromotionPartController;

use App\Models\PromotionPart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromotionPartRequest extends FormRequest
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
    public function rules(): array
    {
        $promotionPartNotCompleted = PromotionPart::where('student_id', $this->student_id)->where('completed', false)->first();

        if ($promotionPartNotCompleted) {
            abort(422, "Tidak dapat membuat ujian kenaikan juz baru, harap selesaikan ujian kenaikan juz {$promotionPartNotCompleted->part->number}");
        }

        return [
            'part_id' => [
                'required',
                'exists:parts,id',
                Rule::unique('promotion_parts', 'part_id')->where('student_id', $this->student_id)
            ],
            'student_id' => [
                'required',
                Rule::exists('students', 'id')
            ],
            'period_id' => [
                'required',
                Rule::exists('periods', 'id')->where('active', true)
            ],
            'level_id' => [
                'required',
                Rule::exists('levels', 'id')->where('active', true)
            ],
        ];
    }
}
