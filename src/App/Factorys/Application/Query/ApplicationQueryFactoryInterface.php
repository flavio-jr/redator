<?php

namespace App\Factorys\Application\Query;

use App\Repositories\ApplicationRepository\Query\ApplicationQueryInterface;

interface ApplicationQueryFactoryInterface
{
    public function getApplicationQuery(): ApplicationQueryInterface;
}