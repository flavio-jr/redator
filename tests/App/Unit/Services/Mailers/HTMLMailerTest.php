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

        $this->swipeMailEnv();
        $this->htmlMailer = $this->container->get('HTMLMailer');
    }

    private function swipeMailEnv()
    {
        putenv('MAIL_HOST=' . getenv('MAIL_TEST_HOST'));
        putenv('MAIL_USER=' . getenv('MAIL_TEST_USER'));
        putenv('MAIL_PASSWORD=' . getenv('MAIL_TEST_PASSWORD'));
        putenv('MAIL_PORT=' . getenv('MAIL_TEST_PORT'));
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