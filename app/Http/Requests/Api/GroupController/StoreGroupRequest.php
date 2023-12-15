<?php

namespace App\Http\Requests\Api\GroupController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest
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
            'period_id' => [
                'required',
                Rule::exists('periods', 'id')->where('active', true)
            ],
            'level_id' => [
                'required',
                Rule::exists('levels', 'id')->where('active', true)
            ],
            'user_id' => [
                Rule::exists('users', 'id')
            ],
            'student_ids' => [
                'array'
            ],
            'student_ids.*' => [
                Rule::exists('students', 'id')->where('active', true)
            ]
        ];
    }
}
