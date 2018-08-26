<?php

namespace App\Repositories\PublicationRepository\Modify;

interface PublicationModifierInterface
{
    /**
     * Modify some publication fields
     * 
     * @method modify
     * @param array $change
     */
    public function modify(
        string $publicationTitle,
        string $applicationName,
        array $change
    );
}