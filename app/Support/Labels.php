<?php

namespace App\Support;

use Illuminate\Support\Str;

class Labels
{
    /** "APPLY NOW — FREE COUNSELLING" → "Apply now — free counselling" (leaves mixed-case labels untouched). */
    public static function button(string $label): string
    {
        if ($label !== mb_strtoupper($label)) {
            return $label;
        }

        return Str::ucfirst(mb_strtolower($label));
    }
}
