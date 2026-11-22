<?php

namespace App\Models;

use Illuminate\Support\Collection;

/**
 * Counselling, profile-evaluation and contact-page enquiries
 * (stored in storage/app/private/leads/enquiries).
 */
class Enquiry extends Lead
{
    public const TYPES = [
        'counselling' => 'Counselling session',
        'evaluation' => 'Profile evaluation',
        'contact' => 'Contact page enquiry',
    ];

    public const QUALIFICATIONS = ['Diploma', 'Higher Secondary', "Bachelor's Degree", "Master's Degree"];

    public const COURSES = [
        'Computer Science & AI',
        'Mechanical & Automotive Engineering',
        'Electrical & Electronics',
        'Data Science & Analytics',
        'Germany Ausbildung (Vocational)',
        'Nursing & Healthcare',
        'MBA & International Business',
        "dMAT Guidance (Master's in Germany)",
    ];

    public const INTAKES = ['Winter Intake', 'Summer Intake'];

    protected static function folder(): string
    {
        return 'enquiries';
    }

    protected static function searchable(): array
    {
        return ['full_name', 'email', 'phone'];
    }

    /** Admin list filters: enquiry type, status and free-text search. */
    public static function filteredBy(?string $type, ?string $status, ?string $term): Collection
    {
        return static::filtered($status, $term)
            ->when($type && array_key_exists($type, self::TYPES), fn ($c) => $c->where('type', $type))
            ->values();
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst((string) $this->type);
    }

    /** Reference shown to the applicant and in the admin panel, e.g. EF-00012. */
    public function reference(): string
    {
        return 'EF-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }
}
