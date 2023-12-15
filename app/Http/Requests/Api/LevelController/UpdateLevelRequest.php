<?php

namespace App\Http\Requests\Api\LevelController;

use App\Rules\EvenNumberRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLevelRequest extends FormRequest
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
                'string',
                'unique:levels,name,' . $this->level->id . ',id'
            ],
            'number' => [
                'integer',
                'min:1'
            ],
            'active' => [
                'boolean'
            ],
            'group_capacity' => [
                'integer',
                'min:2',
                new EvenNumberRule()
            ],
            'revision_task_type' => [
                'in:setoran sempurna,pertanyaan,acak'
            ],
            'revision_quarter_portion' => [
                'in:1,2,4,8'
            ],
            'connection_block_portion' => [
                'integer',
                'min:1'
            ],
            'memorization_block_portion' => [
                'integer',
                'min:1'
            ],
            'max_promotion_recitation_mistake' => [
                'integer',
                'min:1'
            ],
            'max_promotion_question_mistake' => [
                'integer',
                'min:1'
            ],
            'max_revision_recitation_mistake' => [
                'integer',
                'min:1'
            ],
            'max_revision_question_mistake' => [
                'integer',
                'min:1'
            ],
            'max_memorization_mistake' => [
                'integer',
                'min:1'
            ]
        ];
    }
}
