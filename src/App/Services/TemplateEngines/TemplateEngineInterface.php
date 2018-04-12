<?php

namespace App\Services\TemplateEngines;

interface TemplateEngineInterface
{
    public function setParams(array $params);

    public function setTemplatesPath(string $path);

    public function render(string $viewPath): string;
}