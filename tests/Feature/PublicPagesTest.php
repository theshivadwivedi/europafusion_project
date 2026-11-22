<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Exam;
use App\Models\Page;
use App\Models\Service;
use App\Models\University;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public static function pages(): array
    {
        return array_map(fn ($p) => [$p], [
            '/', '/destinations', '/study-in-germany', '/services', '/exam-prep', '/contact', '/b2b',
            '/study-in-finland', '/study-in-netherlands', '/study-in-lithuania', '/study-in-poland',
            '/study-in-latvia', '/study-in-spain', '/study-in-italy', '/study-in-austria',
            '/germany/studienkolleg', '/germany/bachelor', '/germany/master', '/germany/medicine',
            '/privacy-policy', '/terms-conditions', '/disclaimer', '/refund-policy',
        ]);
    }

    #[DataProvider('pages')]
    public function test_page_renders(string $path): void
    {
        $this->get($path)
            ->assertOk()
            ->assertSee('EUROPA', false)
            ->assertSee('data-enquiry="counselling"', false)
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_no_database_is_configured_or_needed(): void
    {
        // phpunit.xml points DB_CONNECTION at a connection that does not exist, so any
        // database call anywhere in the app would throw – and every page test above passes.
        $this->expectException(InvalidArgumentException::class);

        DB::connection()->getPdo();
    }

    public function test_navigation_lists_all_destinations_and_services(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (['study-in-finland', 'study-in-netherlands', 'study-in-spain', 'study-in-germany'] as $slug) {
            $this->assertStringContainsString('/'.$slug, $html);
        }
        $this->assertStringContainsString('services#visa-guidance', $html);
        $this->assertStringContainsString('Technical University of Munich', $html);
    }

    public function test_country_page_renders_its_content_blocks(): void
    {
        $this->get('/study-in-lithuania')
            ->assertOk()
            ->assertSee('Vilnius University')
            ->assertSee('Frequently Asked Questions')
            ->assertSee('Can I study in Lithuania entirely in English?')
            ->assertSee('Explore other destinations');
    }

    public function test_germany_hub_links_its_four_pathways(): void
    {
        $response = $this->get('/study-in-germany')->assertOk();

        foreach (['studienkolleg', 'bachelor', 'master', 'medicine'] as $pathway) {
            $response->assertSee('/germany/'.$pathway, false);
        }
    }

    public function test_exam_prep_lists_tests_and_faqs(): void
    {
        $this->get('/exam-prep')->assertOk()->assertSee('IELTS')->assertSee('GRE')->assertSee('faq-acc', false);
    }

    public function test_legal_page_shows_last_updated_date(): void
    {
        $this->get('/privacy-policy')->assertOk()->assertSee('Last updated: January 2026')->assertDontSee('<p>Last Updated:', false);
    }

    public function test_content_files_are_valid_and_complete(): void
    {
        $this->assertCount(9, Destination::all());
        $this->assertCount(12, Page::all());
        $this->assertCount(4, Page::legal());
        $this->assertCount(11, Service::all());
        $this->assertCount(11, Exam::all());
        $this->assertCount(21, University::all());

        foreach (Page::all() as $page) {
            $this->assertNotNull($page->destination, "{$page->slug} has no matching destination");
            $this->assertNotEmpty($page->sections, "{$page->slug} has no sections");
        }
    }

    public function test_legacy_urls_redirect_permanently(): void
    {
        $this->get('/about')->assertRedirect('/')->assertStatus(301);
        $this->get('/finland')->assertRedirect('/study-in-finland')->assertStatus(301);
        $this->get('/germany-master')->assertRedirect('/germany/master')->assertStatus(301);
        $this->get('/study-in-germany/bachelor')->assertRedirect('/germany/bachelor')->assertStatus(301);
        $this->get('/exams')->assertRedirect('/exam-prep')->assertStatus(301);
        $this->get('/terms')->assertRedirect('/terms-conditions')->assertStatus(301);
    }

    public function test_unknown_pages_return_404(): void
    {
        $this->get('/study-in-mars')->assertNotFound()->assertSee('Page not found');
        $this->get('/germany/pharmacy')->assertNotFound();
    }

    public function test_sitemap_and_robots(): void
    {
        $sitemap = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
        $sitemap->assertSee('/study-in-spain', false)->assertSee('/germany/medicine', false)->assertSee('/refund-policy', false);
        $this->assertSame(23, substr_count($sitemap->getContent(), '<url>'));

        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /admin');
    }
}
