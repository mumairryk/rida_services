<?php

namespace App\Http\Requests\Registration;

use App\Http\Requests\BaseRequest;

class CreateAccountRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'dial_code' => 'required',
            'phone' => 'required',
            'password' => 'required|min:6',
            'user_type_id' => 'nullable|exists:account_type,id',
            'user_device_token' => 'nullable',
            'user_device_type' => 'nullable',
            'activity_type_id' => 'nullable|exists:activity_type,id',
            'business_type_id' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'email.unique' => 'Email is already taken',
            'dial_code.required' => 'Dial code is required',
            'phone.required' => 'Phone number is required',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'user_type_id.required' => 'Account type is required',
            'user_type_id.exists' => 'Account type is invalid',

        ];
    }
}
