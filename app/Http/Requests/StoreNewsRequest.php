<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // You can implement authorization logic here if needed.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnailPath' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048', // Validate as a file (optional)
            'url' => 'required|url',
            'posedBy' => 'required|exists:users,id', // Ensuring posedBy exists in the users table
        ];
    }
}
