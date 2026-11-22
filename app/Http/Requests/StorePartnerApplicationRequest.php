<?php

namespace App\Http\Requests;

use App\Models\PartnerApplication;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePartnerApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => is_string($this->email) ? strtolower(trim($this->email)) : $this->email,
            'interests' => array_values(array_filter((array) $this->input('interests', []), 'is_string')),
        ]);
    }

    public function rules(): array
    {
        return [
            'org_name' => ['required', 'string', 'min:2', 'max:160'],
            'org_type' => ['required', Rule::in(PartnerApplication::ORG_TYPES)],
            'city' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', 'max:80'],
            'contact_person' => ['required', 'string', 'min:2', 'max:120'],
            'designation' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:190'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s\-()]{7,20}$/'],
            'students_per_year' => ['nullable', Rule::in(PartnerApplication::VOLUMES)],
            'interests' => ['array', 'max:10'],
            'interests.*' => [Rule::in(PartnerApplication::INTERESTS)],
            'message' => ['nullable', 'string', 'max:3000'],
        ];
    }

    public function messages(): array
    {
        return [
            'org_name.required' => 'Please enter your organisation’s name.',
            'org_type.required' => 'Please choose your organisation type.',
            'contact_person.required' => 'Please tell us who we should speak to.',
            'phone.regex' => 'Please enter a valid phone number (digits, spaces and + only).',
            'email.email' => 'That email address doesn’t look right.',
        ];
    }

    public function attributes(): array
    {
        return [
            'org_name' => 'organisation name',
            'org_type' => 'organisation type',
            'contact_person' => 'contact person',
            'students_per_year' => 'estimated students per year',
        ];
    }
}
