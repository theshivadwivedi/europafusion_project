@extends('admin.layout')
@section('title', $application->org_name)
@section('content')
    <a href="{{ route('admin.partners.index') }}" class="small"><i class="bi bi-arrow-left"></i> All partner requests</a>
    <div class="d-flex flex-wrap align-items-center gap-3 mt-2 mb-4">
        <h1 class="admin-title">{{ $application->org_name }}</h1>
        <span class="status s-{{ $application->status }}">{{ $application->statusLabel() }}</span>
    </div>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="panel">
                <dl class="detail-list">
                    <dt>Organisation type</dt><dd>{{ $application->org_type }}</dd>
                    <dt>Location</dt><dd>{{ $application->city }}, {{ $application->state }}</dd>
                    <dt>Contact person</dt><dd>{{ $application->contact_person }} — {{ $application->designation }}</dd>
                    <dt>Work email</dt><dd><a href="mailto:{{ $application->email }}">{{ $application->email }}</a></dd>
                    <dt>Phone / WhatsApp</dt><dd><a href="tel:{{ preg_replace('/[^+0-9]/', '', $application->phone) }}">{{ $application->phone }}</a></dd>
                    <dt>Students / referrals per year</dt><dd>{{ $application->students_per_year ?? '—' }}</dd>
                    <dt>Areas of interest</dt><dd>@forelse ($application->interests ?? [] as $i)<span class="badge text-bg-light border me-1 mb-1">{{ $i }}</span>@empty — @endforelse</dd>
                    <dt>Message</dt><dd style="white-space: pre-line">{{ $application->message ?: '—' }}</dd>
                    <dt>Received</dt><dd>{{ $application->created_at->format('d M Y, H:i') }} ({{ $application->created_at->diffForHumans() }})</dd>
                    <dt>IP address</dt><dd class="small text-secondary">{{ $application->ip_address }}</dd>
                </dl>
            </div>
        </div>
        <div class="col-lg-4">
            @include('admin.partials.status-form', ['record' => $application, 'statuses' => \App\Models\PartnerApplication::STATUSES, 'action' => route('admin.partners.update', $application), 'deleteAction' => route('admin.partners.destroy', $application)])
        </div>
    </div>
@endsection
