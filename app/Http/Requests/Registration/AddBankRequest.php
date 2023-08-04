<?php

namespace App\Http\Requests\Registration;

use App\Http\Requests\BaseRequest;

class AddBankRequest extends BaseRequest
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
            'account_no' => 'required',
            'bank_name' => 'required',
            'iban_code' => 'required',
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
        ];
    }


}
