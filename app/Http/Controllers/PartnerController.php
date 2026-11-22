<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnerApplicationRequest;
use App\Mail\PartnerApplicationAcknowledgement;
use App\Mail\PartnerApplicationReceived;
use App\Models\PartnerApplication;
use App\Support\LeadNotifier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function show(): View
    {
        return view('pages.b2b', [
            'orgTypes' => PartnerApplication::ORG_TYPES,
            'volumes' => PartnerApplication::VOLUMES,
            'interests' => PartnerApplication::INTERESTS,
        ]);
    }

    public function store(StorePartnerApplicationRequest $request): JsonResponse|RedirectResponse
    {
        if (! filled($request->input('website'))) {
            $application = PartnerApplication::create($request->validated() + [
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 490, ''),
            ]);

            LeadNotifier::send(config('site.notify_email'), new PartnerApplicationReceived($application));

            if (config('site.send_acknowledgement')) {
                LeadNotifier::send($application->email, new PartnerApplicationAcknowledgement($application));
            }
        }

        $org = (string) $request->input('org_name');

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'organisation' => $org], 201);
        }

        return redirect()->route('b2b')->with('partner_success', $org)->withFragment('b2b-form');
    }
}
