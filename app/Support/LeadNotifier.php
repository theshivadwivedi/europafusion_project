<?php

namespace App\Support;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Sends lead emails without ever failing the form submission:
 * the lead is already saved (storage/app/private/leads), so a mail outage is only logged.
 */
class LeadNotifier
{
    public static function send(string $to, Mailable $mail): bool
    {
        try {
            Mail::to($to)->send($mail);

            return true;
        } catch (Throwable $e) {
            Log::warning('Lead email could not be sent', ['to' => $to, 'mail' => $mail::class, 'error' => $e->getMessage()]);

            return false;
        }
    }
}
