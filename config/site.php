<?php

/*
|--------------------------------------------------------------------------
| Site / company details
|--------------------------------------------------------------------------
| Single source of truth for contact details shown in the header, footer,
| contact page, emails and structured data. Values that differ between
| environments can be overridden from .env.
*/

return [
    'name' => 'EUROPA FUSION',
    'legal_name' => 'EUROPA FUSION PRIVATE LIMITED',
    'cin' => 'U80302TN2021PTC146401',
    'tagline' => 'Your Journey. Our Guidance. Your Global Future.',
    'specialism' => 'Germany & Europe Specialist',
    'description' => 'is a premier study-abroad guidance organisation, helping students explore international education opportunities through personalised counselling, university applications, language preparation, and visa guidance.',

    'phone' => env('SITE_PHONE', '+91 99747 98803'),
    'whatsapp' => env('SITE_WHATSAPP', '919974798803'), // digits only, used for wa.me links
    'email' => env('SITE_EMAIL', 'contact@europafusion.io'),
    'website' => 'europafusion.io',

    'registered_office' => 'Office No. 715A, No. 769, Spencer Plaza, Anna Salai, Chennai, Tamil Nadu – 600002, India',

    'offices' => [
        [
            'name' => 'South Junction Office – Kochi',
            'lines' => ['63/3115 G, Karshaka Road', 'Near South Railway Station', 'Ernakulam – 682016, Kerala'],
            'phones' => ['+91 75930 66771', '+91 75930 66776'],
        ],
    ],

    'b2b_hours' => 'Mon–Sat, 10am–7pm IST',

    'social' => [
        'instagram' => env('SOCIAL_INSTAGRAM', 'https://www.instagram.com/europafusion/'),
        'facebook' => env('SOCIAL_FACEBOOK', 'https://www.facebook.com/profile.php?id=61580822580581'),
        'linkedin' => env('SOCIAL_LINKEDIN', 'https://www.linkedin.com/company/freie-admits/'),
        // The reference site has no YouTube URL yet; the icon is hidden while this is empty.
        'youtube' => env('SOCIAL_YOUTUBE', ''),
    ],

    // Where new enquiry / partner notifications are sent.
    'notify_email' => env('ENQUIRY_NOTIFY_EMAIL', 'contact@europafusion.io'),
    // Send an acknowledgement email to the person who submitted a form.
    'send_acknowledgement' => (bool) env('ENQUIRY_SEND_ACK', true),

    // Folder where form submissions are saved as JSON files (no database is used).
    // Keep it outside the public web root and include it in your backups.
    'leads_path' => env('LEADS_STORAGE_PATH', storage_path('app/private/leads')),

    'disclaimer' => 'Admission decisions are made by individual universities. Visa decisions are made by the relevant immigration authorities. EUROPA FUSION provides guidance and assistance throughout the application process.',
];
