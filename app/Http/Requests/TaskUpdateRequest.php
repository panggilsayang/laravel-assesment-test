<?php

namespace App\Http\Requests;

use App\Models\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required','string'],
            'description' => ['required','string'],
            'status' => ['required','array'],
            'status.*' => ['required',Rule::in(array_keys(TaskStatus::statusLists()))],
            'assignee_id' => ['required','integer','exists:users,id'],
            'due_dates' => ['required','date']
        ];
    }
}
