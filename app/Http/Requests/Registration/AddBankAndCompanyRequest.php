<?php

namespace App\Http\Requests\Registration;

use App\Http\Requests\BaseRequest;

class AddBankAndCompanyRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'commercial_license' => 'required',
            'commercial_reg_no' => 'required',
            'associated_license' => 'required',
            'user_id' => 'required|exists:users,id|integer',
            'account_no' => 'required',
            'bank_name' => 'required',
            'iban_code' => 'required|numeric',
        ];
    }

    function messages()
    {
        return [
            'user_id.required' => 'User id is required',
            'user_id.exists' => 'User id is invalid',
            'account_no.required' => 'Account number is required',
            'bank_name.required' => 'Bank name is required',
            'iban_code.required' => 'IBAN code is required',
            'commercial_license.required' => 'Commercial license is required',
            'commercial_reg_no.required' => 'Commercial registration number is required',
            'associated_license.required' => 'Associated license is required',
        ];
    }
}


