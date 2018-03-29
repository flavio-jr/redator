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

    public function update(Request $request, Response $response, array $args)
    {
        try {
            $data = $request->getParsedBody();

            $updated = $this->applicationRepository->update($args['app_id'], $data);

            if ($updated) {
                return $response->write('Application successfully registered')->withStatus(200);
            }

            return $response->write('The app could not be updated')->withStatus(500);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }

    public function destroy(Request $request, Response $response, array $args)
    {
        $deleted = $this->applicationRepository->destroy($args['app_id']);

        if ($deleted) {
            return $response->write('Application deleted with success')->withStatus(200);
        }

        return $response->write('Error trying to delete application')->withStatus(500);
    }
}