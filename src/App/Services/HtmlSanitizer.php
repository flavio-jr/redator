<?php

namespace App\Services;

use HTMLPurifier;

class HtmlSanitizer
{
    private $htmlPurifier;

    public function __construct(HTMLPurifier $htmlPurifier)
    {
        $this->htmlPurifier = $htmlPurifier;
    }

    public function sanitize(string $html): string
    {
        return $this->htmlPurifier->purify($html);
    }
}