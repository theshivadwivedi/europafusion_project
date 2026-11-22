<?php

namespace Tests\Feature;

use App\Mail\PartnerApplicationAcknowledgement;
use App\Mail\PartnerApplicationReceived;
use App\Models\PartnerApplication;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PartnerApplicationTest extends TestCase
{
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'org_name' => 'Sunrise International School',
            'org_type' => 'School / Junior College',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'contact_person' => 'Priya Menon',
            'designation' => 'Principal',
            'email' => 'principal@sunrise.test',
            'phone' => '+91 98200 00000',
            'students_per_year' => '50 – 150',
            'interests' => ["Bachelor's Programs", 'Referral Partnership'],
            'message' => 'Campus visit in March?',
        ], $overrides);
    }

    public function test_partner_request_is_saved_to_a_file(): void
    {
        Mail::fake();

        $this->postJson('/b2b', $this->payload())->assertCreated()->assertJson(['organisation' => 'Sunrise International School']);

        $this->assertFileExists(config('site.leads_path').'/partners/000001.json');

        $application = PartnerApplication::find(1);
        $this->assertSame('Sunrise International School', $application->org_name);
        $this->assertSame(["Bachelor's Programs", 'Referral Partnership'], $application->interests);
        $this->assertSame('new', $application->status);

        Mail::assertSent(PartnerApplicationReceived::class, fn ($m) => $m->hasTo(config('site.notify_email')));
        Mail::assertSent(PartnerApplicationAcknowledgement::class, fn ($m) => $m->hasTo('principal@sunrise.test'));
    }

    public function test_partner_request_is_validated(): void
    {
        $this->postJson('/b2b', $this->payload(['org_type' => 'Pirate ship', 'interests' => ['Moon base'], 'email' => 'x']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['org_type', 'interests.0', 'email']);

        $this->assertCount(0, PartnerApplication::all());
    }

    public function test_plain_form_post_redirects_to_form_with_flash(): void
    {
        Mail::fake();

        $this->post('/b2b', $this->payload())
            ->assertRedirect(route('b2b').'#b2b-form')
            ->assertSessionHas('partner_success', 'Sunrise International School');
    }

    public function test_honeypot_partner_submissions_are_discarded(): void
    {
        Mail::fake();

        $this->postJson('/b2b', $this->payload(['website' => 'spam']))->assertCreated();

        $this->assertCount(0, PartnerApplication::all());
        Mail::assertNothingSent();
    }
}
