<?php

namespace Tests\App\Unit\Services\TemplateEngines;

use Tests\TestCase;
use App\Services\TemplateEngines\TwigEngine;

class TwigEngineTest extends TestCase
{
    /**
     * @var TwigEngine
     */
    private $twigEngine;

    public function setUp()
    {
        parent::setUp();

        $this->twigEngine = $this->container->get('TwigEngine');
    }

    public function testReplaceEngineTagGracefully()
    {
        $templateName = __DIR__ . '/twigengine.twig';
        $template = '<h1>Hello, {{ name }}</h1>';

        file_put_contents($templateName, $template);

        $this->twigEngine->setParams(['name' => 'test']);
        $this->twigEngine->setTemplatesPath(__DIR__);

        $render = $this->twigEngine->render('twigengine');

        unlink($templateName);

        $this->assertNotNull($render);
    }
}