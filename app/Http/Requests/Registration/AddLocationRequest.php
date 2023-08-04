<?php

namespace App\Http\Requests\Registration;

use App\Http\Requests\BaseRequest;

class AddLocationRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'location_name' => 'required',
            'lattitude' => 'required',
            'longitude' => 'required',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'location_name.required' => 'Location is required',
            'lattitude.required' => 'Latitude is required',
            'longitude.required' => 'Longitude is required',
            'user_id.required' => 'User id is required',
            'user_id.exists' => 'User id is invalid',
        ];
    }
}
