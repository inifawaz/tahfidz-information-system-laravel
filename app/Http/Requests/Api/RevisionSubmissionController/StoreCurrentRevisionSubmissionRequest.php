<?php

namespace App\Http\Requests\Api\RevisionSubmissionController;

use Illuminate\Foundation\Http\FormRequest;

class StoreCurrentRevisionSubmissionRequest extends FormRequest
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
        if ($this->revisionPart->tasks()->where('completed', false)->count() == 0) {
            abort(422, "Murojaah juz {$this->revisionPart->part->number} berhasil diselesaikan");
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
