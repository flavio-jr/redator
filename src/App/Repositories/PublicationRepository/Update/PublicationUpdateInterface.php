<?php

namespace App\Repositories\PublicationRepository\Update;

interface PublicationUpdateInterface
{
    /**
     * Updates an publication
     * @param string $publicationSlug
     * @param string $applicationSlug
     * @param array $data
     * @return bool
     */
    public function update(string $publicationSlug, string $applicationSlug, array $data): bool;
}