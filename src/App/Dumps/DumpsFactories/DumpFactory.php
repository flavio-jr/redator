<?php

namespace App\Dumps\DumpsFactories;

use App\Dumps\DumpInterface;

class DumpFactory implements DumpFactoryInterface
{
    public function mock(DumpInterface $dump, int $amount, array $override = [])
    {
        $mocks = [];

        for ($i = 0; $i < $amount ; $i++) {
            $mocks[] = $dump->make($override);
        }

        return $mocks;
    }

    public function produce(DumpInterface $dump, int $amount, array $override = [])
    {
        $mocks = [];

        for ($i = 0; $i < $amount ; $i++) {
            $mocks[] = $dump->create($override);
        }

        return $mocks;
    }
}