<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            "user_data_id"              =>      "required",
            "appoinemtn_with"           =>      "required",
            "appoinemtnt_date"          =>      "required|date",
            "appoinemtnt_time"          =>      "required",
            "note"                      =>      "required",
        ];
    }
}
