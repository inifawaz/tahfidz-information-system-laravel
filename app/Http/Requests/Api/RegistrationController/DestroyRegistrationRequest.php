<?php

namespace App\Http\Requests\Api\RegistrationController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DestroyRegistrationRequest extends FormRequest
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
            'student_ids' => [
                'required',
                'array'
            ],
            'student_ids.*' => [
                'required',
                Rule::exists('students', 'id')->where('active', true),
                Rule::exists('registrations', 'student_id')->where('period_id', $this->period_id)->where('level_id', $this->level_id),
            ]
        ];
    }
}
