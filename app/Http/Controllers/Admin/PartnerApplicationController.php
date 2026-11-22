<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerApplication;
use App\Support\CsvExport;
use App\Support\Pagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PartnerApplicationController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.partners.index', [
            'applications' => Pagination::of($this->filtered($request), $request),
            'filters' => $request->only(['status', 'q']),
        ]);
    }

    public function show(PartnerApplication $partner): View
    {
        return view('admin.partners.show', ['application' => $partner]);
    }

    public function update(Request $request, PartnerApplication $partner): RedirectResponse
    {
        $partner->update($request->validate([
            'status' => ['required', Rule::in(array_keys(PartnerApplication::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]));

        return back()->with('status', 'Partner request updated.');
    }

    public function destroy(PartnerApplication $partner): RedirectResponse
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('status', 'Partner request deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        return CsvExport::download(
            'partner-requests-'.now()->format('Y-m-d').'.csv',
            ['ID', 'Status', 'Organisation', 'Type', 'City', 'State', 'Contact person', 'Designation', 'Email', 'Phone', 'Students / year', 'Interests', 'Message', 'Notes', 'Submitted'],
            $this->filtered($request)->map(fn (PartnerApplication $a) => [
                $a->id, $a->statusLabel(), $a->org_name, $a->org_type, $a->city, $a->state, $a->contact_person, $a->designation,
                $a->email, $a->phone, $a->students_per_year, implode('; ', $a->interests ?? []), $a->message, $a->admin_notes,
                $a->created_at?->format('Y-m-d H:i'),
            ])
        );
    }

    private function filtered(Request $request): Collection
    {
        return PartnerApplication::filtered($this->param($request, 'status'), $this->param($request, 'q'));
    }
}
