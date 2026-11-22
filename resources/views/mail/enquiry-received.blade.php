<x-mail::message>
# New website lead: {{ strtolower($enquiry->typeLabel()) }}

**{{ $enquiry->full_name }}** submitted an enquiry on the website.

<x-mail::table>
| Field | Details |
|:--|:--|
| Name | {{ $enquiry->full_name }} |
| Email | {{ $enquiry->email }} |
| Phone | {{ $enquiry->phone }} |
| Qualification | {{ $enquiry->qualification ?: '—' }} |
| Percentage / GPA | {{ $enquiry->gpa ?: '—' }} |
| Preferred course | {{ $enquiry->preferred_course ?: '—' }} |
| Preferred country | {{ $enquiry->preferred_country ?: '—' }} |
| Preferred intake | {{ $enquiry->preferred_intake ?: '—' }} |
| Submitted | {{ $enquiry->created_at->format('d M Y, H:i') }} |
</x-mail::table>

@if ($enquiry->message)
**Message**

{{ $enquiry->message }}
@endif

<x-mail::button :url="route('admin.enquiries.show', $enquiry)">
Open in admin panel
</x-mail::button>

Reply to this email to answer {{ $enquiry->full_name }} directly.
</x-mail::message>
