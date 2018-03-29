<?php

namespace App\Database;

interface EntityInterface
{
    public function getId(): string;

    public function toArray(): array;

    public function fromArray(array $data): void;
}