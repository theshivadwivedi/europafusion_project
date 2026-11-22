@extends('admin.layout')
@section('title', 'Student enquiries')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="admin-title">Student enquiries</h1>
        <a href="{{ route('admin.enquiries.export', request()->query()) }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download"></i> Export CSV</a>
    </div>
    <form class="panel mb-3 row g-2 align-items-end" method="GET">
        <div class="col-md-4"><label class="form-label small fw-semibold" for="q">Search</label><input class="form-control" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Name, email or phone"></div>
        <div class="col-md-3"><label class="form-label small fw-semibold" for="type">Type</label><select class="form-select" id="type" name="type"><option value="">All types</option>@foreach (\App\Models\Enquiry::TYPES as $k => $l)<option value="{{ $k }}" @selected(($filters['type'] ?? '') === $k)>{{ $l }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label small fw-semibold" for="status">Status</label><select class="form-select" id="status" name="status"><option value="">All statuses</option>@foreach (\App\Models\Enquiry::STATUSES as $k => $l)<option value="{{ $k }}" @selected(($filters['status'] ?? '') === $k)>{{ $l }}</option>@endforeach</select></div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-primary flex-fill">Filter</button>@if (array_filter($filters))<a href="{{ route('admin.enquiries.index') }}" class="btn btn-light" title="Clear filters"><i class="bi bi-x-lg"></i></a>@endif</div>
    </form>
    <div class="panel p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-admin table-hover">
                <thead><tr><th>Ref</th><th>Name</th><th>Type</th><th>Profile</th><th>Country / intake</th><th>Status</th><th>Received</th></tr></thead>
                <tbody>
                    @forelse ($enquiries as $e)
                        <tr>
                            <td class="small text-secondary">{{ $e->reference() }}</td>
                            <td><a href="{{ route('admin.enquiries.show', $e) }}" class="fw-semibold">{{ $e->full_name }}</a><div class="small text-secondary">{{ $e->email }} · {{ $e->phone }}</div></td>
                            <td class="small">{{ $e->typeLabel() }}</td>
                            <td class="small">{{ $e->qualification ?? '—' }}<div class="text-secondary">{{ $e->gpa }}</div></td>
                            <td class="small">{{ $e->preferred_country ?? '—' }}<div class="text-secondary">{{ $e->preferred_intake }}</div></td>
                            <td><span class="status s-{{ $e->status }}">{{ $e->statusLabel() }}</span></td>
                            <td class="small text-nowrap">{{ $e->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-secondary py-5">No enquiries match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $enquiries->links() }}</div>
@endsection
