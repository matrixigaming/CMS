<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CustomerPostRequest extends Request
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
        'name' => 'required|max:50',
        //'email' => 'required|email|max:64|unique:customers,email,'.$this->id,
        //'mobile' => 'required|unique:customers,mobile,'.$this->id,
        'code' => 'unique',
        ];
    }
}
