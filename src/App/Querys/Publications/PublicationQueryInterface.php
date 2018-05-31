<?php

namespace App\Querys\Publications;

use App\Entities\Application;

interface PublicationQueryInterface
{
    /**
     * Query the publications of the given application
     * @method get
     * @param Application $application
     * @return array
     */
    public function get(Application $application, array $filters = []): array;
}