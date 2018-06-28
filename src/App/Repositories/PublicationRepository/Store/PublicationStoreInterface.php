<?php

namespace App\Repositories\PublicationRepository\Store;

use App\Entities\Publication;

interface PublicationStoreInterface
{
    /**
     * Stores an new publication
     * @method store
     * @param string $application
     * @param array $data
     * @return Publication
     */
    public function store(string $application, array $data): ?Publication;
}