<x-mail::message>
# New B2B partnership request

**{{ $application->org_name }}** ({{ $application->org_type }}) would like to partner with {{ config('site.name') }}.

<x-mail::table>
| Field | Details |
|:--|:--|
| Organisation | {{ $application->org_name }} |
| Type | {{ $application->org_type }} |
| Location | {{ $application->city }}, {{ $application->state }} |
| Contact person | {{ $application->contact_person }} ({{ $application->designation }}) |
| Email | {{ $application->email }} |
| Phone | {{ $application->phone }} |
| Students / referrals per year | {{ $application->students_per_year ?: '—' }} |
| Areas of interest | {{ $application->interests ? implode(', ', $application->interests) : '—' }} |
</x-mail::table>

@if ($application->message)
**Message**

{{ $application->message }}
@endif

<x-mail::button :url="route('admin.partners.show', $application)">
Open in admin panel
</x-mail::button>
</x-mail::message>
