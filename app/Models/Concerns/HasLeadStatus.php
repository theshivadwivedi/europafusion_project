<?php

namespace App\Models\Concerns;

/**
 * Shared status workflow for leads (enquiries and partner applications).
 */
trait HasLeadStatus
{
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'in_progress' => 'In progress',
        'closed' => 'Closed',
    ];

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }
}
