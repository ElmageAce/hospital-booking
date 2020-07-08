<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
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
            'patient' => 'bail|required|integer|different:doctor|exists:users,id',
            'doctor' => 'bail|required|integer|different:patient|exists:users,id',
            'title' => 'bail|required|string',
            'desc' => 'bail|nullable|string',
            'date' => 'bail|required|string|date'
        ];
    }
}
