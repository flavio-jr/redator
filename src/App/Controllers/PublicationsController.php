<?php

namespace App\Controllers;

use App\Repositories\PublicationRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Filters\PublicationFilter;

final class PublicationsController
{
    /**
     * The repository for Publication entity
     * @var PublicationRepository
     */
    private $publicationRepository;

    /**
     * The filter for publications
     * @var PublicationFilter
     */
    private $publicationFilter;

    public function __construct(
        PublicationRepository $publicationRepository,
        PublicationFilter $publicationFilter
    ) {
        $this->publicationRepository = $publicationRepository;
        $this->publicationFilter = $publicationFilter;
    }

    /**
     * Get all publications of an app
     * @method getPublications
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function getPublications(Request $request, Response $response, array $args)
    {
        $parameters = $request->getQueryParams();
        $parameters['application'] = $args['application_id'];

        $publications = $this->publicationFilter
            ->setFilters($parameters)
            ->filterByTitle()
            ->filterByCategory()
            ->filterByApplication()
            ->filterByMinDate()
            ->filterByMaxDate()
            ->get();

        return $response->write(json_encode(['publications' => $publications]))->withStatus(200);
    }

    /**
     * Creates an new publication
     * @method store
     * @param Request $request
     * @param Response $response
     */
    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $this->publicationRepository->create($data);

            return $response->write('Publication successfully created')->withStatus(200);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }

    /**
     * Updates an publication data
     * @method update
     * @param Request $request
     * @param Response $response
     */
    public function update(Request $request, Response $response, array $args)
    {
        try {            
            $id = $args['publication_id'];
            $data = $request->getParsedBody();

            $updated = $this->publicationRepository->update($id, $data);

            if ($updated) {
                return $response->write('Publication successfully registered')->withStatus(200);
            }

            return $response->write('The publication could not be updated')->withStatus(500);

            return $response->write('Publication successfully updated')->withStatus(200);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }

    /**
     * Destroy an publication
     * @method destroy
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function destroy(Request $request, Response $response, array $args)
    {
        $id = $args['publication_id'];

        if ($this->publicationRepository->destroy($id)) {
            return $response->write('Publication successfully deleted')->withStatus(200);
        }

        return $response->write('Failed to delete publication')->withStatus(500);
    }
}