<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Mail\EnquiryAcknowledgement;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Support\LeadNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class EnquiryController extends Controller
{
    /**
     * Stores counselling, profile-evaluation and contact-page enquiries.
     * Answers JSON for the AJAX modal and redirects back for plain form posts.
     */
    public function store(StoreEnquiryRequest $request): JsonResponse|RedirectResponse
    {
        // Honeypot: real visitors never see or fill this field.
        if (filled($request->input('website'))) {
            return $this->respond($request, $request->input('full_name', ''));
        }

        $enquiry = Enquiry::create($request->validated() + [
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 490, ''),
        ]);

        LeadNotifier::send(config('site.notify_email'), new EnquiryReceived($enquiry));

        if (config('site.send_acknowledgement')) {
            LeadNotifier::send($enquiry->email, new EnquiryAcknowledgement($enquiry));
        }

        return $this->respond($request, $enquiry->full_name, $enquiry);
    }

    private function respond(StoreEnquiryRequest $request, string $name, ?Enquiry $enquiry = null): JsonResponse|RedirectResponse
    {
        $firstName = Str::of($name)->trim()->explode(' ')->first() ?: 'there';

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'name' => $firstName,
                'reference' => $enquiry?->reference(),
            ], 201);
        }

        return back()
            ->with('enquiry_success', $firstName)
            ->withFragment('enquiry');
    }
}
