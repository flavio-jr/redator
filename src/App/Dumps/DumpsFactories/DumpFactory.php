<?php

namespace App\Dumps\DumpsFactories;

use App\Dumps\DumpInterface;

class DumpFactory implements DumpFactoryInterface
{
    /**
     * Create many dumps, without persisting them
     * @method mock
     * @param DumpInterface $dump
     * @param int $amount
     * @param array $override
     */
    public function mock(DumpInterface $dump, int $amount, array $override = [])
    {
        $mocks = [];

        for ($i = 0; $i < $amount ; $i++) {
            $mocks[] = $dump->make($override);
        }

        return $mocks;
    }

    /**
     * Create many dumps, persisting them
     * @method produce
     * @param DumpInterface $dump
     * @param int $amount
     * @param array $override
     */
    public function produce(DumpInterface $dump, int $amount, array $override = [])
    {
        $mocks = [];

        for ($i = 0; $i < $amount ; $i++) {
            $mocks[] = $dump->create($override);
        }

        return $mocks;
    }
}