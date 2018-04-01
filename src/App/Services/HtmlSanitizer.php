<?php

namespace App\Services;

use HTMLPurifier;

class HtmlSanitizer
{
    /**
     * The HTHML Purifier library
     * @var HTMLPurifier
     */
    private $htmlPurifier;

    public function __construct(HTMLPurifier $htmlPurifier)
    {
        $this->htmlPurifier = $htmlPurifier;
    }

    /**
     * Sanitize an html input
     * @method sanitize
     * @param string $html
     * @return string
     */
    public function sanitize(string $html): string
    {
        return $this->htmlPurifier->purify($html);
    }
}