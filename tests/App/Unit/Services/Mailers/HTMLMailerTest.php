<?php

namespace Tests\App\Unit\Services\Mailers;

use Tests\TestCase;
use App\Services\Mailers\HTMLMailer;

class HTMLMailerTest extends TestCase
{
    /**
     * @var HTMLMailer
     */
    private $htmlMailer;

    public function setUp()
    {
        parent::setUp();

        $this->htmlMailer = $this->container->get('HTMLMailer');
    }

    public function testSendSimpleEmail()
    {
        $emailSent = $this->htmlMailer
            ->from('spock@locical.vulcano')
            ->to('kirk@captain.com')
            ->subject('Testing email')
            ->body('<h1>Hello, world</h1>')
            ->send();

        $this->assertTrue($emailSent);
    }
}