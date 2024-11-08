<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditClientUserRequest extends FormRequest
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
            "email" => "required|",
            "roles" => "required",
            // "password" => [
            //     'nullable',
            //     'string',
            //     'min:6',              // must be at least 6 characters in length
            //     'regex:/[a-z]/',      // must contain at least one lowercase letter
            //     'regex:/[A-Z]/',      // must contain at least one uppercase letter
            //     'regex:/[0-9]/',      // must contain at least one digit
            //     'regex:/[@$!%*#?&]/', // must contain a special character
            // ],
            "company" => "required",
            //"brands" => "required|array",
            //"sites" => "required|array",
            //"screens" => "required|array",
            "mobile" =>  "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|max:11",
            //"pass_int" => "required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:4|max:4",
            // "pass_int" => "required|regex:/^([0-9\s\-\+\(\)]*)$/|min:4|max:4",
            "level" => "required|string",
        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            
            'pass_int.min' => 'The PIN Code must be at least 4 characters.',
            'pass_int.max' => 'The PIN Code must be at least 4 characters.'
            ,
        ];
    }
}
