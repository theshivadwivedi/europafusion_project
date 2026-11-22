<?php

namespace App\Http\Requests;

use App\Models\Destination;
use App\Models\Enquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'full_name' => is_string($this->full_name) ? trim(preg_replace('/\s+/', ' ', $this->full_name)) : $this->full_name,
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
            'phone' => is_string($this->phone) ? trim($this->phone) : $this->phone,
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_keys(Enquiry::TYPES))],
            'full_name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s\-()]{7,20}$/'],
            'email' => ['required', 'string', 'email:rfc', 'max:190'],
            'qualification' => ['nullable', Rule::in(Enquiry::QUALIFICATIONS)],
            'gpa' => ['required', 'string', 'max:40'],
            'preferred_course' => ['nullable', Rule::in(Enquiry::COURSES)],
            'preferred_country' => ['nullable', Rule::in(Destination::formOptions())],
            'preferred_intake' => ['nullable', Rule::in(Enquiry::INTAKES)],
            'message' => ['nullable', 'string', 'max:2000'],
            'source_url' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Please tell us your full name.',
            'phone.required' => 'Please add a phone number we can reach you on.',
            'phone.regex' => 'Please enter a valid phone number (digits, spaces and + only).',
            'email.required' => 'Please add your email address.',
            'email.email' => 'That email address doesn’t look right.',
            'gpa.required' => 'Please add your percentage or GPA (e.g. 8.2 CGPA or 78%).',
        ];
    }

    public function attributes(): array
    {
        return [
            'gpa' => 'percentage / GPA',
            'preferred_course' => 'preferred course',
            'preferred_country' => 'preferred country',
            'preferred_intake' => 'preferred intake',
        ];
    }
}
