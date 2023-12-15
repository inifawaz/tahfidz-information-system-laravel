<?php

namespace App\Http\Requests\Api\MembershipController;

use App\Models\Group;
use App\Models\Period;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMembershipRequest extends FormRequest
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
            'group_id' => [
                'bail',
                'required',
                'exists:groups,id',
                function ($attribute, $value, $fail) {
                    $group = Group::find($value);
                    $period = Period::find($group->period_id);
                    if (!$period->active) {
                        $fail('The selected group id is invalid');
                    }
                }
            ],
            'student_ids' => [
                'required',
                'array'
            ],
            'student_ids.*' => [
                'required',
                Rule::exists('students', 'id'),
                Rule::unique('memberships', 'student_id')->where('group_id', $this->group_id)
            ]
        ];
    }
}
