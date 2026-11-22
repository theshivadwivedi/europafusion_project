<?php

namespace Tests\Feature;

use App\Mail\EnquiryAcknowledgement;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'type' => 'counselling',
            'full_name' => 'Aryan Sharma',
            'phone' => '+91 98765 43210',
            'email' => 'Aryan@Example.com',
            'qualification' => "Bachelor's Degree",
            'gpa' => '8.2 CGPA',
            'preferred_course' => 'Computer Science & AI',
            'preferred_country' => 'Germany',
            'preferred_intake' => 'Winter Intake',
        ], $overrides);
    }

    public function test_modal_enquiry_is_saved_to_a_file_and_emails_are_sent(): void
    {
        Mail::fake();

        $this->postJson('/enquiries', $this->payload())
            ->assertCreated()
            ->assertJson(['ok' => true, 'name' => 'Aryan', 'reference' => 'EF-00001']);

        $file = config('site.leads_path').'/enquiries/000001.json';
        $this->assertFileExists($file);

        $saved = json_decode(file_get_contents($file), true);
        $this->assertSame(1, $saved['id']);
        $this->assertSame('aryan@example.com', $saved['email']);
        $this->assertSame('Germany', $saved['preferred_country']);
        $this->assertSame('new', $saved['status']);
        $this->assertNotEmpty($saved['created_at']);

        $enquiry = Enquiry::find(1);
        $this->assertSame('Counselling session', $enquiry->typeLabel());
        $this->assertSame('EF-00001', $enquiry->reference());

        Mail::assertSent(EnquiryReceived::class, fn ($m) => $m->hasTo(config('site.notify_email')));
        Mail::assertSent(EnquiryAcknowledgement::class, fn ($m) => $m->hasTo('aryan@example.com'));
    }

    public function test_references_keep_counting_and_are_never_reused(): void
    {
        Mail::fake();

        $this->postJson('/enquiries', $this->payload())->assertJsonPath('reference', 'EF-00001');
        $this->postJson('/enquiries', $this->payload())->assertJsonPath('reference', 'EF-00002');

        Enquiry::find(2)->delete();

        $this->postJson('/enquiries', $this->payload())->assertJsonPath('reference', 'EF-00003');
        $this->assertSame([3, 1], Enquiry::all()->pluck('id')->all());
    }

    public function test_required_fields_are_validated(): void
    {
        $this->postJson('/enquiries', ['type' => 'evaluation'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['full_name', 'phone', 'email', 'gpa']);

        $this->assertCount(0, Enquiry::all());
    }

    public function test_options_must_come_from_the_allowed_lists(): void
    {
        $this->postJson('/enquiries', $this->payload([
            'type' => 'spam',
            'preferred_country' => 'Atlantis',
            'phone' => 'call me maybe',
            'email' => 'not-an-email',
        ]))->assertUnprocessable()->assertJsonValidationErrors(['type', 'preferred_country', 'phone', 'email']);
    }

    public function test_plain_form_post_redirects_back_with_success_message(): void
    {
        Mail::fake();

        $this->from('/contact')
            ->post('/enquiries', $this->payload(['type' => 'contact', 'message' => 'Hello']))
            ->assertRedirect('/contact#enquiry')
            ->assertSessionHas('enquiry_success', 'Aryan');

        $this->assertSame('contact', Enquiry::all()->first()->type);
        $this->assertSame('Hello', Enquiry::all()->first()->message);
    }

    public function test_honeypot_submissions_are_discarded_silently(): void
    {
        Mail::fake();

        $this->postJson('/enquiries', $this->payload(['website' => 'http://spam.test']))->assertCreated();

        $this->assertCount(0, Enquiry::all());
        Mail::assertNothingSent();
    }

    public function test_submissions_are_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/enquiries', $this->payload())->assertCreated();
        }
        $this->postJson('/enquiries', $this->payload())->assertTooManyRequests();
    }

    public function test_without_smtp_emails_are_written_to_the_mail_log_even_with_a_strict_log_level(): void
    {
        $log = $this->storageDir.'/mail.log';
        config([
            'mail.default' => 'log',
            'logging.channels.mail.driver' => 'single',
            'logging.channels.mail.path' => $log,
            'logging.channels.single.level' => 'warning',
        ]);

        $this->postJson('/enquiries', $this->payload())->assertCreated();

        $this->assertFileExists($log);
        $this->assertStringContainsString('To: aryan@example.com', file_get_contents($log));
        $this->assertStringContainsString('To: '.config('site.notify_email'), file_get_contents($log));
    }

    public function test_a_mail_failure_does_not_lose_the_enquiry(): void
    {
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.host' => '127.0.0.1', 'mail.mailers.smtp.port' => 1, 'mail.mailers.smtp.timeout' => 1]);

        $this->postJson('/enquiries', $this->payload())->assertCreated()->assertJsonPath('reference', 'EF-00001');

        $this->assertCount(1, Enquiry::all());
    }
}
