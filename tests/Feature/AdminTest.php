<?php

namespace Tests\Feature;

use App\Models\Enquiry;
use App\Models\PartnerApplication;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminTest extends TestCase
{
    private const PASSWORD = 'Corr3ct-Horse!';

    private function admin(): Authenticatable
    {
        $this->artisan('admin:create', ['--name' => 'Test Admin', '--email' => 'Admin@Example.com', '--password' => self::PASSWORD])
            ->expectsOutputToContain('Created admin account admin@example.com')
            ->assertSuccessful();

        return Auth::getProvider()->retrieveByCredentials(['email' => 'admin@example.com']);
    }

    private function enquiry(array $overrides = []): Enquiry
    {
        return Enquiry::create($overrides + [
            'type' => 'evaluation', 'full_name' => 'Meera Nair', 'email' => 'meera@example.com',
            'phone' => '+91 90000 11111', 'gpa' => '78%', 'preferred_country' => 'Germany',
        ]);
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/enquiries')->assertRedirect('/admin/login');
        $this->get('/admin/partners')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee('Sign in');
    }

    public function test_admin_accounts_are_stored_as_hashes_in_a_file(): void
    {
        $this->admin();

        $file = config('auth.providers.users.path');
        $this->assertFileExists($file);
        $this->assertStringNotContainsString(self::PASSWORD, file_get_contents($file));
        $this->assertStringContainsString('$2y$', file_get_contents($file));
    }

    public function test_weak_passwords_are_refused(): void
    {
        $this->artisan('admin:create', ['--name' => 'X', '--email' => 'x@example.com', '--password' => 'short'])->assertFailed();

        $this->assertFileDoesNotExist(config('auth.providers.users.path'));
    }

    public function test_running_admin_create_again_resets_the_password(): void
    {
        $this->admin();
        $this->artisan('admin:create', ['--name' => 'Test Admin', '--email' => 'admin@example.com', '--password' => 'N3w-Passw0rd!'])
            ->expectsOutputToContain('Updated admin account admin@example.com')
            ->assertSuccessful();

        $this->post('/admin/login', ['email' => 'admin@example.com', 'password' => self::PASSWORD])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => 'admin@example.com', 'password' => 'N3w-Passw0rd!'])->assertRedirect('/admin');
    }

    public function test_admin_can_log_in_with_valid_credentials_only(): void
    {
        $admin = $this->admin();

        $this->post('/admin/login', ['email' => 'admin@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => 'nobody@example.com', 'password' => self::PASSWORD])->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post('/admin/login', ['email' => 'ADMIN@example.com', 'password' => self::PASSWORD, 'remember' => '1'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        $this->get('/admin')->assertOk()->assertSee('Test Admin');
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_admin_can_manage_enquiries(): void
    {
        $enquiry = $this->enquiry();
        $this->enquiry(['full_name' => 'Rahul Verma', 'email' => 'rahul@example.com', 'type' => 'contact']);
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Meera Nair')->assertSee('Rahul Verma');
        $this->actingAs($admin)->get('/admin/enquiries?q=meera')->assertOk()->assertSee('meera@example.com')->assertDontSee('rahul@example.com');
        $this->actingAs($admin)->get('/admin/enquiries?type=contact')->assertOk()->assertSee('Rahul Verma')->assertDontSee('Meera Nair');
        $this->actingAs($admin)->get("/admin/enquiries/{$enquiry->id}")->assertOk()->assertSee('78%')->assertSee('EF-00001');
        $this->actingAs($admin)->get('/admin/enquiries/999')->assertNotFound();
        $this->actingAs($admin)->get('/admin/enquiries/abc')->assertNotFound();

        $this->actingAs($admin)->patch("/admin/enquiries/{$enquiry->id}", ['status' => 'contacted', 'admin_notes' => 'Called'])
            ->assertRedirect();
        $this->assertSame('contacted', $enquiry->fresh()->status);
        $this->assertSame('Called', $enquiry->fresh()->admin_notes);
        $this->actingAs($admin)->get('/admin/enquiries?status=contacted')->assertSee('Meera Nair')->assertDontSee('Rahul Verma');

        $this->actingAs($admin)->patch("/admin/enquiries/{$enquiry->id}", ['status' => 'bogus'])->assertSessionHasErrors('status');

        $csv = $this->actingAs($admin)->get('/admin/enquiries/export');
        $csv->assertOk()->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Meera Nair', $csv->streamedContent());

        $this->actingAs($admin)->delete("/admin/enquiries/{$enquiry->id}")->assertRedirect('/admin/enquiries');
        $this->assertNull(Enquiry::find($enquiry->id));
        $this->assertCount(1, Enquiry::all());
    }

    public function test_enquiry_list_is_paginated(): void
    {
        foreach (range(1, 17) as $i) {
            $this->enquiry(['full_name' => "Student {$i}", 'email' => "s{$i}@example.com"]);
        }
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/enquiries')->assertOk()->assertSee('Student 17')->assertDontSee('Student 2<', false);
        $this->actingAs($admin)->get('/admin/enquiries?page=2')->assertOk()->assertSee('Student 1<', false)->assertDontSee('Student 17');
    }

    public function test_admin_can_manage_partner_requests(): void
    {
        $partner = PartnerApplication::create([
            'org_name' => 'Sunrise School', 'org_type' => 'School / Junior College', 'city' => 'Pune', 'state' => 'MH',
            'contact_person' => 'Priya', 'designation' => 'Principal', 'email' => 'p@sunrise.test', 'phone' => '+91 90000 22222',
            'interests' => ['Referral Partnership'],
        ]);
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/partners?q=pune')->assertOk()->assertSee('Sunrise School');
        $this->actingAs($admin)->get("/admin/partners/{$partner->id}")->assertOk()->assertSee('Referral Partnership');
        $this->actingAs($admin)->patch("/admin/partners/{$partner->id}", ['status' => 'in_progress'])->assertRedirect();
        $this->assertSame('in_progress', $partner->fresh()->status);
        $this->assertStringContainsString('Sunrise School', $this->actingAs($admin)->get('/admin/partners/export')->streamedContent());
        $this->actingAs($admin)->delete("/admin/partners/{$partner->id}")->assertRedirect('/admin/partners');
        $this->assertNull(PartnerApplication::find($partner->id));
    }

    public function test_csv_export_neutralises_spreadsheet_formulas(): void
    {
        $this->enquiry(['full_name' => '=HYPERLINK("http://evil")']);

        $body = $this->actingAs($this->admin())->get('/admin/enquiries/export')->streamedContent();
        $this->assertStringContainsString("'=HYPERLINK", $body);
    }
}
