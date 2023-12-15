<?php

namespace App\Http\Requests\Api\PeriodController;

use App\Models\Period;
use Illuminate\Foundation\Http\FormRequest;

class UpdateActivePeriodRequest extends FormRequest
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
        $activePeriod = Period::where('active', true)->firstOrFail();

        return [
            'name' => [
                'required',
                'string',
                'unique:periods,name,' . $activePeriod->id . ',id'
            ],
            'start_date' => [
                'date_format:Y-m-d'
            ],
            'end_date' => [
                'date_format:Y-m-d'
            ],
            'active' => [
                'boolean'
            ]
        ];
    }
}
