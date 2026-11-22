<?php

namespace App\Models;

/**
 * B2B partner registrations (stored in storage/app/private/leads/partners).
 */
class PartnerApplication extends Lead
{
    public const ORG_TYPES = [
        'School / Junior College',
        'Degree College / University',
        'Coaching / Training Centre',
        'Corporate / Company HR',
        'Education Consultant / Agency',
        'NGO / Skill Development Body',
        'Other',
    ];

    public const VOLUMES = ['Less than 50', '50 – 150', '150 – 300', '300 – 500', '500+'];

    public const INTERESTS = [
        'Germany (dMAT / Public Universities)',
        'Finland Public Universities',
        'Ireland Universities',
        'Ausbildung / Vocational Programs',
        "Bachelor's Programs",
        "Master's Programs",
        'MBA / Management',
        'Campus Workshops & Webinars',
        'Co-branded Collateral',
        'Referral Partnership',
    ];

    protected static function folder(): string
    {
        return 'partners';
    }

    protected static function searchable(): array
    {
        return ['org_name', 'contact_person', 'email', 'city'];
    }
}
