<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoanStatusRequest extends FormRequest
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
            'loan_status' => 'required|exists:loan_statuses,id',
            'remarks' => 'required_if:loan_status,'.loanStatusByName('Declined')->id,
            'loan_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
          'remarks.required_if' => 'The remarks field is required when status is declined'
        ];
    }
}
