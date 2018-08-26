<?php

namespace App\Repositories\PublicationRepository\Modify;

use App\Repositories\PublicationRepository\Finder\PublicationFinderInterface as PublicationFinder;
use App\Services\Persister\PersisterInterface as Persister;
use App\Exceptions\EntityNotFoundException;

final class PublicationModifier implements PublicationModifierInterface
{
    /**
     * @var PublicationFinder
     */
    private $publicationFinder;

    /**
     * @var Persister
     */
    private $persister;

    public function __construct(
        PublicationFinder $publicationFinder,
        Persister $persister
    ) {
        $this->publicationFinder = $publicationFinder;
        $this->persister = $persister;
    }

    public function modify(
        string $publicationTitle,
        string $applicationName,
        array $change
    ) {
        $publication = $this
            ->publicationFinder
            ->find($publicationTitle, $applicationName);
            
        if (!$publication) {
            throw new EntityNotFoundException('Publication');
        }

        if (isset($change['status'])) {
            $publication->setStatus($change['status']);
        }

        $this
            ->persister
            ->persist($publication);
    }
}
