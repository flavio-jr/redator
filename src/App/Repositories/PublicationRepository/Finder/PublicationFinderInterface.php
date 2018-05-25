<?php

namespace App\Repositories\PublicationRepository\Finder;

use App\Entities\Publication;

interface PublicationFinderInterface
{
    /**
     * Finds an publication by a given identifier
     * @method find
     * @param string $identifier
     * @return Publication|null
     */
    public function find(string $identifier, string $applicationSlug): ?Publication;
}