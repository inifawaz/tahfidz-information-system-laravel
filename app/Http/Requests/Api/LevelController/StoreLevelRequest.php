<?php

namespace App\Http\Requests\Api\LevelController;

use App\Rules\EvenNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLevelRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                'string',
                'unique:levels,name'
            ],
            'group_capacity' => [
                'required',
                'integer',
                'min:2',
                new EvenNumberRule()
            ],
            'revision_task_type' => [
                'required',
                'in:setoran sempurna,pertanyaan,acak'
            ],
            'revision_quarter_portion' => [
                'required',
                'in:1,2,4,8'
            ],
            'connection_block_portion' => [
                'required',
                'integer',
                'min:1'
            ],
            'memorization_block_portion' => [
                'required',
                'integer',
                'min:1'
            ],
            'max_promotion_recitation_mistake' => [
                'required',
                'integer',
                'min:1'
            ],
            'max_promotion_question_mistake' => [
                'required',
                'integer',
                'min:1'
            ],
            'max_revision_recitation_mistake' => [
                'required',
                'integer',
                'min:1'
            ],
            'max_revision_question_mistake' => [
                'required',
                'integer',
                'min:1'
            ],
            'max_memorization_mistake' => [
                'required',
                'integer',
                'min:1'
            ]
        ];
    }
}
