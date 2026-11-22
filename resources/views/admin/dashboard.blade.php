@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="admin-title">Dashboard</h1>
        <a href="{{ route('admin.enquiries.export') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-download"></i> Export all enquiries (CSV)</a>
    </div>
    <div class="row g-3 mb-4">
        @foreach ([['New enquiries', $stats['new_enquiries'], 'inbox'], ['Enquiries (30 days)', $stats['enquiries_30d'], 'calendar3'], ['New partner requests', $stats['new_partners'], 'buildings'], ['Total leads', $stats['total_leads'], 'people']] as [$label, $num, $icon])
            <div class="col-sm-6 col-xl-3"><div class="panel kpi d-flex justify-content-between align-items-center"><div><div class="num">{{ $num }}</div><div class="lbl">{{ $label }}</div></div><i class="bi bi-{{ $icon }} fs-3 text-secondary"></i></div></div>
        @endforeach
    </div>
    <div class="row g-3">
        <div class="col-xl-8">
            <div class="panel p-0 overflow-hidden">
                <div class="d-flex justify-content-between align-items-center p-3"><h2 class="h6 m-0 fw-bold">Latest enquiries</h2><a href="{{ route('admin.enquiries.index') }}" class="small">View all</a></div>
                <div class="table-responsive">
                    <table class="table table-admin">
                        <thead><tr><th>Name</th><th>Type</th><th>Country</th><th>Status</th><th>Received</th></tr></thead>
                        <tbody>
                            @forelse ($recentEnquiries as $e)
                                <tr><td><a href="{{ route('admin.enquiries.show', $e) }}" class="fw-semibold">{{ $e->full_name }}</a><div class="small text-secondary">{{ $e->email }}</div></td><td>{{ $e->typeLabel() }}</td><td>{{ $e->preferred_country ?? '—' }}</td><td><span class="status s-{{ $e->status }}">{{ $e->statusLabel() }}</span></td><td class="small">{{ $e->created_at->diffForHumans() }}</td></tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-secondary py-4">No enquiries yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-4 d-grid gap-3 align-content-start">
            <div class="panel">
                <h2 class="h6 fw-bold mb-3">Enquiries by type</h2>
                @php($totalType = max(1, $byType->sum()))
                @foreach (\App\Models\Enquiry::TYPES as $key => $label)
                    <div class="bar-row"><span>{{ $label }}</span><span class="bar"><span style="width: {{ round(($byType[$key] ?? 0) / $totalType * 100) }}%"></span></span><strong class="text-end">{{ $byType[$key] ?? 0 }}</strong></div>
                @endforeach
            </div>
            <div class="panel">
                <h2 class="h6 fw-bold mb-3">Top preferred countries</h2>
                @php($totalC = max(1, $byCountry->max() ?? 1))
                @forelse ($byCountry as $country => $n)
                    <div class="bar-row"><span>{{ $country }}</span><span class="bar"><span style="width: {{ round($n / $totalC * 100) }}%"></span></span><strong class="text-end">{{ $n }}</strong></div>
                @empty
                    <p class="text-secondary small m-0">No data yet.</p>
                @endforelse
            </div>
            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-2"><h2 class="h6 fw-bold m-0">Latest partner requests</h2><a href="{{ route('admin.partners.index') }}" class="small">View all</a></div>
                @forelse ($recentPartners as $p)
                    <div class="py-2 border-bottom small"><a href="{{ route('admin.partners.show', $p) }}" class="fw-semibold">{{ $p->org_name }}</a> <span class="status s-{{ $p->status }} ms-1">{{ $p->statusLabel() }}</span><div class="text-secondary">{{ $p->org_type }} · {{ $p->city }}</div></div>
                @empty
                    <p class="text-secondary small m-0">No partner requests yet.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
