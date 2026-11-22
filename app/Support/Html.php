<?php

namespace App\Support;

use Illuminate\Support\HtmlString;

/**
 * Minimal sanitiser for the small amount of inline markup allowed in page content
 * (see docs/CONTENT_SCHEMA.md). Strips every other tag, event-handler attributes
 * and non-http(s)/relative link targets.
 */
class Html
{
    private const INLINE = '<strong><em><b><i><br><a>';

    private const RICH = '<strong><em><b><i><br><a><p><ul><ol><li><h2><h3><h4>';

    public static function inline(?string $text): HtmlString
    {
        return new HtmlString(self::clean((string) $text, self::INLINE));
    }

    public static function rich(?string $text): HtmlString
    {
        return new HtmlString(self::clean((string) $text, self::RICH));
    }

    /** Wrap the given substring of $text in a highlight span (used for hero headings). */
    public static function highlight(string $text, ?string $part, string $class = 'hl'): HtmlString
    {
        $safe = e($text);
        if ($part && str_contains($text, $part)) {
            $safe = str_replace(e($part), '<span class="'.$class.'">'.e($part).'</span>', $safe);
        }

        return new HtmlString($safe);
    }

    private static function clean(string $html, string $allowed): string
    {
        $html = strip_tags($html, $allowed);
        // drop on*="" handlers and style attributes
        $html = preg_replace('/\s(on\w+|style)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        // only allow http(s), mailto, tel and relative hrefs
        $html = preg_replace_callback('/href\s*=\s*("|\')(.*?)\1/i', function ($m) {
            $url = trim(html_entity_decode($m[2]));

            return preg_match('#^(https?:|mailto:|tel:|/|\#)#i', $url) ? 'href="'.e($url).'"' : 'href="#"';
        }, $html);

        return $html;
    }
}
