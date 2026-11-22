@extends('admin.layout')
@section('title', $enquiry->full_name)
@section('content')
    <a href="{{ route('admin.enquiries.index') }}" class="small"><i class="bi bi-arrow-left"></i> All enquiries</a>
    <div class="d-flex flex-wrap align-items-center gap-3 mt-2 mb-4">
        <h1 class="admin-title">{{ $enquiry->full_name }}</h1>
        <span class="status s-{{ $enquiry->status }}">{{ $enquiry->statusLabel() }}</span>
    </div>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="panel">
                <h2 class="h6 fw-bold mb-3">{{ $enquiry->typeLabel() }} · {{ $enquiry->reference() }}</h2>
                <dl class="detail-list">
                    <dt>Email</dt><dd><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></dd>
                    <dt>Phone</dt><dd><a href="tel:{{ preg_replace('/[^+0-9]/', '', $enquiry->phone) }}">{{ $enquiry->phone }}</a> · <a href="https://wa.me/{{ preg_replace('/\D/', '', $enquiry->phone) }}" target="_blank" rel="noopener">WhatsApp</a></dd>
                    <dt>Highest qualification</dt><dd>{{ $enquiry->qualification ?? '—' }}</dd>
                    <dt>Percentage / GPA</dt><dd>{{ $enquiry->gpa ?? '—' }}</dd>
                    <dt>Preferred course</dt><dd>{{ $enquiry->preferred_course ?? '—' }}</dd>
                    <dt>Preferred country</dt><dd>{{ $enquiry->preferred_country ?? '—' }}</dd>
                    <dt>Target intake</dt><dd>{{ $enquiry->preferred_intake ?? '—' }}</dd>
                    <dt>Message</dt><dd style="white-space: pre-line">{{ $enquiry->message ?: '—' }}</dd>
                    <dt>Submitted from</dt><dd class="small">{{ $enquiry->source_url ?: '—' }}</dd>
                    <dt>Received</dt><dd>{{ $enquiry->created_at->format('d M Y, H:i') }} ({{ $enquiry->created_at->diffForHumans() }})</dd>
                    <dt>Last updated</dt><dd>{{ $enquiry->updated_at->format('d M Y, H:i') }}</dd>
                    <dt>IP address</dt><dd class="small text-secondary">{{ $enquiry->ip_address }}</dd>
                </dl>
            </div>
        </div>
        <div class="col-lg-4">
            @include('admin.partials.status-form', ['record' => $enquiry, 'statuses' => \App\Models\Enquiry::STATUSES, 'action' => route('admin.enquiries.update', $enquiry), 'deleteAction' => route('admin.enquiries.destroy', $enquiry)])
        </div>
    </div>
@endsection
