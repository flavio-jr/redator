<?php

namespace App\Controllers\PublicationsController;

use App\Repositories\PublicationRepository\Store\PublicationStoreInterface as PublicationStore;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\UserNotAllowedToWritePublication;
use App\Exceptions\EntityNotFoundException;

final class PublicationStoreController
{
    /**
     * The publication store class
     * @var PublicationStore
     */
    private $publicationStore;

    public function __construct(PublicationStore $publicationStore)
    {
        $this->publicationStore = $publicationStore;
    }

    public function store(Request $request, Response $response, array $args)
    {
        try {
            $publicationCreated = $this->publicationStore
                ->store($args['app'], $request->getParsedBody());

            $response->getBody()
                ->write(json_encode(['app' => $publicationCreated->getSlug()]));

            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (EntityNotFoundException $e) {
            $response->getBody()
                ->write("The given {$e->getEntityName()} was not found");

            return $response->withStatus(404);
        }
    }
}