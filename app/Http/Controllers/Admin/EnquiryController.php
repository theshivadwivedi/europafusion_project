<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Support\CsvExport;
use App\Support\Pagination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnquiryController extends Controller
{
    public function index(Request $request): View
    {
        $enquiries = Pagination::of($this->filtered($request), $request);

        return view('admin.enquiries.index', [
            'enquiries' => $enquiries,
            'filters' => $request->only(['type', 'status', 'q']),
        ]);
    }

    public function show(Enquiry $enquiry): View
    {
        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Enquiry::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $enquiry->update($data);

        return back()->with('status', 'Enquiry updated.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('status', 'Enquiry deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        return CsvExport::download(
            'enquiries-'.now()->format('Y-m-d').'.csv',
            ['Reference', 'Type', 'Status', 'Name', 'Email', 'Phone', 'Qualification', 'GPA', 'Course', 'Country', 'Intake', 'Message', 'Notes', 'Source', 'Submitted'],
            $this->filtered($request)->map(fn (Enquiry $e) => [
                $e->reference(), $e->typeLabel(), $e->statusLabel(), $e->full_name, $e->email, $e->phone,
                $e->qualification, $e->gpa, $e->preferred_course, $e->preferred_country, $e->preferred_intake,
                $e->message, $e->admin_notes, $e->source_url, $e->created_at?->format('Y-m-d H:i'),
            ])
        );
    }

    private function filtered(Request $request): Collection
    {
        return Enquiry::filteredBy($this->param($request, 'type'), $this->param($request, 'status'), $this->param($request, 'q'));
    }
}
