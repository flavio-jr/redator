<?php

namespace App\Repositories\PublicationRepository\Collect;

interface PublicationCollectionInterface
{
    /**
     * Get a collection of publications of the given application
     * @method get
     * @param string $applicationIdentifier
     * @param array $filters
     * @return array
     */
    public function get(string $applicationIdentifier, array $filters = []): array;
}

