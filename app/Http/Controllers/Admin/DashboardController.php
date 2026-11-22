<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\PartnerApplication;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $since = Carbon::now()->subDays(30);
        $enquiries = Enquiry::all();
        $partners = PartnerApplication::all();

        return view('admin.dashboard', [
            'stats' => [
                'new_enquiries' => $enquiries->where('status', 'new')->count(),
                'enquiries_30d' => $enquiries->filter(fn (Enquiry $e) => $e->created_at?->gte($since))->count(),
                'new_partners' => $partners->where('status', 'new')->count(),
                'total_leads' => $enquiries->count() + $partners->count(),
            ],
            'byType' => $enquiries->countBy('type'),
            'byCountry' => $enquiries->whereNotNull('preferred_country')
                ->countBy('preferred_country')
                ->sortDesc()
                ->take(6),
            'recentEnquiries' => $enquiries->take(6),
            'recentPartners' => $partners->take(4),
        ]);
    }
}
