<?php

namespace App\Services\HtmlSanitizer;

use HTMLPurifier;

class HtmlSanitizer implements HtmlSanitizerInterface
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
     * @inheritdoc
     */
    public function sanitize(string $html): string
    {
        return $this->htmlPurifier->purify($html);
    }
}