<?php

namespace App\Http\Requests\Registration;


use App\Http\Requests\BaseRequest;

class DeliveryPartnerRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required',
            
            'res_phone' => 'required',
            'dob' => 'required',
            
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
}
