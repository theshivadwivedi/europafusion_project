@extends('admin.layout')
@section('title', 'B2B partner requests')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="admin-title">B2B partner requests</h1>
        <a href="{{ route('admin.partners.export', request()->query()) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download"></i> Export CSV</a>
    </div>
    <form class="panel mb-3 row g-2 align-items-end" method="GET">
        <div class="col-md-6"><label class="form-label small fw-semibold" for="q">Search</label><input class="form-control" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Organisation, contact, email or city"></div>
        <div class="col-md-4"><label class="form-label small fw-semibold" for="status">Status</label><select class="form-select" id="status" name="status"><option value="">All statuses</option>@foreach (\App\Models\PartnerApplication::STATUSES as $k => $l)<option value="{{ $k }}" @selected(($filters['status'] ?? '') === $k)>{{ $l }}</option>@endforeach</select></div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-primary flex-fill">Filter</button>@if (array_filter($filters))<a href="{{ route('admin.partners.index') }}" class="btn btn-light" title="Clear filters"><i class="bi bi-x-lg"></i></a>@endif</div>
    </form>
    <div class="panel p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead><tr><th>Organisation</th><th>Contact</th><th>Location</th><th>Volume</th><th>Status</th><th>Received</th></tr></thead>
                <tbody>
                    @forelse ($applications as $a)
                        <tr>
                            <td><a href="{{ route('admin.partners.show', $a) }}" class="fw-semibold">{{ $a->org_name }}</a><div class="small text-secondary">{{ $a->org_type }}</div></td>
                            <td class="small">{{ $a->contact_person }} <span class="text-secondary">({{ $a->designation }})</span><div class="text-secondary">{{ $a->email }}</div></td>
                            <td class="small">{{ $a->city }}, {{ $a->state }}</td>
                            <td class="small">{{ $a->students_per_year ?? '—' }}</td>
                            <td><span class="status s-{{ $a->status }}">{{ $a->statusLabel() }}</span></td>
                            <td class="small text-nowrap">{{ $a->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-5">No partner requests match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $applications->links() }}</div>
@endsection
