<?php

namespace App\Repositories\PublicationRepository\Destruction;

interface PublicationDestructionInterface
{
    /**
     * Destroy an publication
     * @method destroy
     * @param string $publicationSlug
     * @param string $applicationSlug
     * @return bool
     */
    public function destroy(string $publicationSlug, string $applicationSlug): bool;
}