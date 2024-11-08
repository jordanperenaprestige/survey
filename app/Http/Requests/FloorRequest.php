<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FloorRequest extends FormRequest
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
            "site_building_id" => "required|int",
            "name" => "required|string",
            
        ];
    }
    /**
     * @return array
     */
    public function messages()
    {
        return [
            'site_building_id.required' => 'The building field is required.',
        ];
    }
}
