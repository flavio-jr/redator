<?php

namespace App\Database;

interface ModelManagerInterface
{
    public function getModel(string $model);

    public function build(array $config);
}