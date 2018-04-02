<?php

namespace App\Controllers;

use App\Repositories\ApplicationRepository;
use Slim\Http\Request;
use Slim\Http\Response;

final class ApplicationsController
{
    /**
     * The application repository
     * @var ApplicationRepository
     */
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * Creates an new application
     * @method store
     * @param Request $request
     * @param Response $response
     */
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

    /**
     * Updates an app data
     * @method update
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
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

    /**
     * Destroy an app
     * @method destroy
     * @param Request $request
     * @param Reponse $response
     * @param array $args
     */
    public function destroy(Request $request, Response $response, array $args)
    {
        $deleted = $this->applicationRepository->destroy($args['app_id']);

        if ($deleted) {
            return $response->write('Application deleted with success')->withStatus(200);
        }

        return $response->write('Error trying to delete application')->withStatus(500);
    }

    /**
     * Get all apps of the logged user
     * @method userApps
     * @param Request $request
     * @param Response $response
     */
    public function userApps(Request $request, Response $response)
    {
        $applications = $this->applicationRepository->getApplicationsByUser();

        if (is_array($applications)) {
            return $response->write(json_encode(['apps' => $applications]))->withStatus(200);
        }

        return $response->write('Failed to retrieve user apps')->withStatus(500);
    }
}