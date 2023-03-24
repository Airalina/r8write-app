<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "dni" => "required|numeric",
            "email" => "required|string|email",
            "site_url" => "sometimes|string",
            "phone" => "sometimes|string",
            "address" => "sometimes|string",
            "description" => "sometimes|string",
        ];
    }
}
