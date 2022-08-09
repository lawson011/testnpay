<?php

namespace App\Http\Requests;

use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NipTransferRequest extends FormRequest
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
            'amount' => 'required',
            'payer'  => 'required|string',
            'payerAccountNumber'  => 'required|string',
            'receiverAccountNumber'  => 'required|string',
            'receiverAccountType'  => 'required|string',
            'receiverBankCode'  => 'required|string',
            'receiverName' => 'required|string',
            "Narration" => "string",
            "NIPSessionID" => "string",
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
