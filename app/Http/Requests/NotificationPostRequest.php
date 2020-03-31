<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class NotificationPostRequest extends Request
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
            'type' => 'required',
            'event_type' => 'required|unique:notifications,id,'.$this->get('id'),
            'title' => 'required|min:3|max:25',
            'content' => 'required|min:5',
            'icon' => 'mimes:png,jpg,jpeg'
        ];
    }
    
}
