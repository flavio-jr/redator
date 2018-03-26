<?php

namespace App\Dumps;

use App\Services\Persister;
use Faker\Generator;

interface DumpInterface
{
    public function __construct(Generator $faker, Persister $persister);

    public function make(array $override = []);

    public function create(array $override = []);
}