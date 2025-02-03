<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// app/Http/Requests/StoreShowRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowRequest extends FormRequest
{
    // Authorize the request (for permissions)
    public function authorize()
    {
        return true; // Allow the request by default
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_path' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'schedule_frequency' => 'required|in:daily,weekly,specific', // Frequency options
            'schedule_time' => 'required|date_format:H:i:s', // Validating time format
            'specific_days' => 'nullable|array', // Required for 'specific' frequency
            'specific_days.*' => 'nullable|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', // Specific days validation
            'weekly_day' => 'nullable|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', // Required for 'weekly' frequency
        ];
    }

    public function messages()
    {
        return [
            'specific_days.*.in' => 'The selected days must be valid weekdays.',
            'weekly_day.in' => 'The selected day must be a valid weekday.',
        ];
    }
}
