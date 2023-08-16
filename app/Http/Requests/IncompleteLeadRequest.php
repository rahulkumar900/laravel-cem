<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IncompleteLeadRequest extends FormRequest
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
            'mobile'            =>      'required|integer',
            'assign_by'         =>      'required|integer',
            'assign_to'         =>      'required|integer',
            'followup_call_on'  =>      'required|date_format:Y-m-d H:i:s',
            'comments'          =>      'required|string',
            'enquiry_date'      =>      'required|date',
            'is_done'           =>      'required|integer|between:0,2',
            'name'              =>      'string',
            'religion'          =>      'required|integer|between:1,9',
            'caste'             =>      'required|integer',
            'education'         =>      'integer',
            'gender'            =>      'required|between:1,2',
            'alt_mobile'        =>      'integer',
            'height'            =>      'integer|between:24,96',
            'manglik'           =>      'integer',
            'marital_status'    =>      'required|between:1,7',
            'occupation'        =>      'integer',
            'birth_date'        =>      'required|date_format:Y-m-d H:i:s',
            'weight'            =>      'integer|between:10,500',
            'speed'             =>      'required|string',
            'relation'          =>      'required|integer|between:1,8'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'       => false,
            'message'       => 'Validation errors',
            'save_status'   => 'N',
            'data'          => $validator->errors(),
        ]));
    }
}
