<x-mail::message>
# Thank you, {{ $application->contact_person }}

We have received the partnership request for **{{ $application->org_name }}**. Our B2B team will contact you within one business day to discuss the partnership model that suits you best.

If you would like to speak to us sooner, call {{ config('site.phone') }} ({{ config('site.b2b_hours') }}) or reply to this email.

Warm regards,<br>
The {{ config('site.name') }} B2B team
</x-mail::message>
