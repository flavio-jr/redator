<?php

namespace App\Repositories\ApplicationRepository\Store;

use App\Entities\Application;

interface ApplicationStoreInterface
{
    /**
     * Stores a new application
     * @method store
     * @param array $data
     * @return Application
     */
    public function store(array $data): Application;
}