<?php

namespace App\Http\Requests\Lead;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'dni' => 'required|numeric',
            'email' => 'required|string|email',
            'password' => 'required|string',
            'site_url' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ];
    }
}
