<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchedulesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (int)$this->doctor === (int)auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'bail|required|integer|exists:appointments,id',
            'doctor' => 'bail|required|integer|exists:users,id',
            'date' => 'bail|required|string|date',
            'status' => 'bail|required|string|max:15',
        ];
    }
}
