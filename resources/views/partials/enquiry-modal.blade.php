<div class="modal fade enquiry-modal" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-grid">
                <aside class="modal-aside">
                    <p class="eyebrow">Profile-based guidance</p>
                    <h2>The student first. The pathway second.</h2>
                    <p>Share a few details and a counsellor will review your profile before we speak — so the first conversation is already useful.</p>
                    <ul>
                        <li><i class="bi bi-check2-circle" aria-hidden="true"></i>Free, no-obligation first session</li>
                        <li><i class="bi bi-check2-circle" aria-hidden="true"></i>Public &amp; private options compared honestly</li>
                        <li><i class="bi bi-check2-circle" aria-hidden="true"></i>dMAT, APS &amp; visa requirements explained</li>
                        <li><i class="bi bi-check2-circle" aria-hidden="true"></i>We usually respond within one working day</li>
                    </ul>
                    <x-star-ring class="aside-art" />
                </aside>

                <div class="modal-main">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div data-view="form">
                        <div class="type-switch" role="group" aria-label="Enquiry type">
                            <button type="button" data-switch="counselling" class="is-active" aria-pressed="true">Counselling session</button>
                            <button type="button" data-switch="evaluation" aria-pressed="false">Profile evaluation</button>
                        </div>
                        <h2 class="h3 mb-1" id="enquiryModalTitle" data-copy="title">Book a free counselling session</h2>
                        <p class="text-muted-ef small mb-4" data-copy="lead">Tell us about your background. We understand the student first and recommend the pathway second.</p>

                        <form action="{{ route('enquiries.store') }}" method="POST" novalidate>
                            @csrf
                            <input type="hidden" name="type" value="counselling">
                            <input type="hidden" name="source_url" value="">
                            <div data-form-alert></div>
                            @include('partials.enquiry-fields', ['prefix' => 'm'])
                            <button type="submit" class="btn btn-primary w-100 mt-4 btn-submit">
                                <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                                <span data-copy="submit">Book my free session</span> <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </button>
                            <p class="modal-note"><i class="bi bi-shield-lock" aria-hidden="true"></i><span>By submitting you agree to be contacted about your enquiry (<a href="{{ route('legal', 'privacy-policy') }}">privacy policy</a>). {{ config('site.disclaimer') }}</span></p>
                        </form>
                    </div>

                    <div data-view="success" class="success-panel" hidden tabindex="-1" aria-live="polite">
                        <div class="success-icon"><i class="bi bi-check-lg" aria-hidden="true"></i></div>
                        <h3>Thank you, <span data-fill="name">there</span>!</h3>
                        <span class="ref" data-fill="reference" hidden></span>
                        <p class="text-muted-ef">Your enquiry has been received. Here is what happens next:</p>
                        <ol class="next-steps">
                            <li><span><strong>Counsellor review:</strong> we review your academic profile and target programmes.</span></li>
                            <li><span><strong>Profile contact:</strong> we call you to understand your goals, budget and timelines.</span></li>
                            <li><span><strong>Study pathways:</strong> we discuss suitable options for <strong data-fill="country">your chosen destination</strong>.</span></li>
                            <li><span><strong>Action plan:</strong> you receive clear guidance on applications, deadlines and next steps.</span></li>
                        </ol>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done, thank you</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
