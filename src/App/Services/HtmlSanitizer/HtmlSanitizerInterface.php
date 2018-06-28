<?php

namespace App\Services\HtmlSanitizer;

interface HtmlSanitizerInterface
{
    /**
     * Sanitize an html input
     * @method sanitize
     * @param string $html
     * @return string The sanitized HTML
     */
    public function sanitize(string $html): string;
}