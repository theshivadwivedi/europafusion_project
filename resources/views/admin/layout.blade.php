<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Dashboard') · EUROPA FUSION Admin</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite('resources/js/admin.js')
</head>
<body class="admin">
@auth
    <div class="admin-shell">
        <aside class="admin-side">
            <a class="brand-mini" href="{{ route('admin.dashboard') }}"><x-emblem /> <span>EUROPA FUSION<small>LEAD DESK</small></span></a>
            @php($newEnq = \App\Models\Enquiry::countWithStatus('new'))
            @php($newPartners = \App\Models\PartnerApplication::countWithStatus('new'))
            <nav class="d-grid gap-1">
                <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="{{ route('admin.enquiries.index') }}" @class(['active' => request()->routeIs('admin.enquiries.*')])><i class="bi bi-inbox"></i> Student enquiries @if ($newEnq)<span class="badge rounded-pill text-bg-warning">{{ $newEnq }}</span>@endif</a>
                <a href="{{ route('admin.partners.index') }}" @class(['active' => request()->routeIs('admin.partners.*')])><i class="bi bi-buildings"></i> B2B partners @if ($newPartners)<span class="badge rounded-pill text-bg-warning">{{ $newPartners }}</span>@endif</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> View website</a>
            </nav>
            <div class="side-foot">
                <div class="mb-2">Signed in as<br><strong class="text-white">{{ auth()->user()->name }}</strong></div>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-left"></i> Sign out</button></form>
            </div>
        </aside>
        <main class="admin-main">
            @if (session('status'))
                <div class="alert alert-success d-flex gap-2 align-items-center"><i class="bi bi-check-circle"></i>{{ session('status') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
@else
    @yield('content')
@endauth
</body>
</html>
