<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadPostRequest extends FormRequest
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
            'mobile'                    =>      'required|integer',
            'profile_creating_for'      =>      'required',
            'lead_gender'               =>      'required',
            'full_name'                 =>      'required|string',
            'religion'                  =>      'required',
            'castes'                    =>      'required_if:religion,1-Hindu,1-Sikh,1-Jain',
            'birth_date'                =>      'required|date',
            'marital_status'            =>      'required',
            'user_height'               =>      'required|numeric',
            'weight'                    =>      'required|numeric',
            'yearly_income'             =>      'required|numeric',
            'current_city'              =>      'required|string',
            'about_me'                  =>      'nullable',
            'lead_source'               =>      'required',
            'interest_level'            =>      'required',
            'followup_date'             =>      'required|date',
            'enquiry_date'              =>      'required|date',
            'followup_comment'          =>      'required|string',
        ];
    }
}
