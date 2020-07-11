<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            'id' => 'bail|required|integer|exists:users,id',
            'name' => 'bail|required|string',
            'avatar' => 'bail|nullable|string|image|max:2048',
            'address' => 'bail|required|string',
            'phone' => 'bail|required|string',
            'dob' => 'bail|required|string|date'
        ];
    }
}
