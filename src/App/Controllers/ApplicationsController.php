<?php

namespace App\Controllers;

use App\Repositories\ApplicationRepository;
use Slim\Http\Request;
use Slim\Http\Response;

final class ApplicationsController
{
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $this->applicationRepository->create($data);

            return $response->write('Application registered with success')->withStatus(200);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }
}