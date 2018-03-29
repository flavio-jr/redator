<?php

namespace App\Dumps\DumpsFactories;

use App\Dumps\DumpInterface;

interface DumpFactoryInterface
{
    public function mock(DumpInterface $dump, int $amount, array $override);

    public function produce(DumpInterface $dump, int $amount, array $override);
}