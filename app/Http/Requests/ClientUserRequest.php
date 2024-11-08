<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientUserRequest extends FormRequest
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
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => "required|email|unique:users",
            "roles" => "required",
            // "password" => [
            //     'required',
            //     //'string',  
            //     'min:4',              // must be at least 6 characters in length
            //     //'regex:/[a-z]/',      // must contain at least one lowercase letter
            //     //'regex:/[A-Z]/',      // must contain at least one uppercase letter
            //     //'regex:/[0-9]/',      // must contain at least one digit
            //     //'regex:/[@$!%*#?&]/', // must contain a special character
            // ],
            "company" => "required",
            "mobile" =>  "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11",
            "pass_int" => "required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:4",
            "level" => "required|string",
        ];
    }

    public function messages()
    {
        return [
            'pass_init.required' => 'The pin code field is required',
            'pass_int.unique:users' => 'The pin code has already been taken',
        ];
    }
    
}
