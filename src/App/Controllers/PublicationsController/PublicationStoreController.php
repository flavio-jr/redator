<?php

namespace App\Controllers\PublicationsController;

use App\Repositories\PublicationRepository\Store\PublicationStoreInterface as PublicationStore;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
        $publicationCreated = $this->publicationStore
            ->store($args['app'], $request->getParsedBody());

        if (!$publicationCreated) {
            $response->getBody()
                ->write('The publication could not be created');

            return $response->withStatus(501);
        }

        $response->getBody()
            ->write(json_encode(['app' => $publicationCreated->getSlug()]));

        return $response->withStatus(201);
    }
}