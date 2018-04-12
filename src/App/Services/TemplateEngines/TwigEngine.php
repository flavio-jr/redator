<?php

namespace App\Services\TemplateEngines;

use Twig_Environment;
use Twig_Loader_Filesystem;

class TwigEngine implements TemplateEngineInterface
{
    /**
     * The path to the template
     * @var string
     */
    private $templatePath;

    /**
     * The params to engine to render
     * @var array
     */
    private $params = array();

    /**
     * The twig template engine
     * @var Twig_Environment
     */
    private $twig;

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function setTemplatesPath(string $path)
    {
        $this->templatePath = $path;
    }

    public function render(string $viewPath): string
    {
        $loader = new Twig_Loader_Filesystem($this->templatePath);
        $twig = new Twig_Environment($loader);

        return $twig->render($viewPath . '.twig', $this->params);
    }
}