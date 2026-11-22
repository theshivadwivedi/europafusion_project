<x-mail::message>
# Thank you, {{ \Illuminate\Support\Str::of($enquiry->full_name)->explode(' ')->first() }}

@if ($enquiry->type === 'contact')
We have received your enquiry. Here is what happens next:
@else
We have received your request for a **{{ strtolower($enquiry->typeLabel()) }}**. Here is what happens next:
@endif

1. **Counsellor review** – a counsellor reviews your profile ({{ $enquiry->qualification ?: 'your qualification' }}{{ $enquiry->gpa ? ', '.$enquiry->gpa : '' }}) and target programmes.
2. **Profile contact** – we contact you to understand your academic profile and career goals.
3. **Study pathways** – we discuss suitable study pathways{{ $enquiry->preferred_country ? ' for '.$enquiry->preferred_country : '' }}.
4. **Action plan** – you receive actionable guidance on applications, timelines and next steps.

Questions in the meantime? Call or WhatsApp us on {{ config('site.phone') }}, or simply reply to this email.

Warm regards,<br>
The {{ config('site.name') }} team

<x-mail::subcopy>
{{ config('site.disclaimer') }}
</x-mail::subcopy>
</x-mail::message>
