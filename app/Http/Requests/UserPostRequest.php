<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserPostRequest extends Request
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
        'first_name' => 'required|max:50',
        'last_name' => 'required|max:50',
        'email' => 'required|email|max:64|unique:users,email,'.$this->id,
        //'password' => 'min:6|max:20|required'.$this->id,//.($this->id) ? '' : 'required',
            'password' => 'min:6|max:20'.($this->id) ? '' : '|required',
        'avatar' => 'mimes:png,jpg,jpeg',
        ];
    }
}
