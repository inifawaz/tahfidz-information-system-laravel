<?php

namespace App\Http\Requests\Api\StudentController;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
            ],
            'date_of_birth' => [
                'required',
                'date_format:Y-m-d'
            ],
            'gender' => [
                'required',
                'in:laki-laki,perempuan'
            ],
            'phone_number' => [
                'string'
            ]
        ];
    }
}
