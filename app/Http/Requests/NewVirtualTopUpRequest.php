<?php

namespace App\Http\Requests;

use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewVirtualTopUpRequest extends FormRequest
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
            'destination'   => 'required|string',
            'alias'         => 'required|string',
            'name'          => 'required|string',
            'description'   => 'required|string',
            'provider'      => 'required|string',
            'amount'        => 'required|string'
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException((new ResponseService())->getErrorResource([
            "message" => $validator->getMessageBag()->first(),
            "field_errors" => $validator->errors()
        ]));
    }
}
