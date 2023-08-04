<?php

namespace App\Http\Requests;

use App\Traits\ApiResponsesTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    use ApiResponsesTrait;
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
            //
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $errors = [];
        $msg = "";
        foreach ($validator->getMessageBag()->toArray() as $key => $errMsg) {

            $msg = $errMsg[0];
            $errors[$key] = $errMsg[0];
        }
        throw new ValidationException($validator, $this->errorResponse($errors, $msg, 422));
    }
}