<?php

namespace App\Http\Requests\Api\RevisionPartController;

use App\Models\PromotionPart;
use App\Models\RevisionPart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRevisionPartRequest extends FormRequest
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
        $promotionPartsCompleted = PromotionPart::where('student_id', $this->student_id)->where('completed', true)->get();
        if ($promotionPartsCompleted->isEmpty()) {
            abort(422, 'Belum ada juz yang telah selesai dihafal');
        }

        $revisionPartNotCompleted = RevisionPart::where('student_id', $this->student_id)->where('completed', false)->first();

        if ($revisionPartNotCompleted) {
            abort(422, "Tidak dapat membuat murojaah juz baru, harap selesaikan murojaah juz {$revisionPartNotCompleted->part->number}");
        }

        return [
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
