<?php

namespace App\Http\Requests\Registration;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class AddVehicleDetailsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'vehicle_type' => 'required',
            'vehicle_front' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'vehicle_back' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'vehicle_registration' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'driving_license' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ];
    }
}
