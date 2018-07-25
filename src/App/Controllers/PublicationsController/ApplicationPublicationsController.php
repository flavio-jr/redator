<?php

namespace App\Controllers\PublicationsController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\PublicationRepository\Collect\PublicationCollectionInterface as PublicationCollection;

final class ApplicationPublicationsController
{
    /**
     * The publication collection repository
     * @var PublicationCollection
     */
    private $publicationCollection;

    public function __construct(PublicationCollection $publicationCollection)
    {
        $this->publicationCollection = $publicationCollection;
    }

    public function get(Request $request, Response $response, array $args)
    {
        $publications = $this->publicationCollection
            ->get(
                $args['app'],
                $request->getQueryParams()
            );

        $response->getBody()
            ->write(json_encode(['publications' => $publications]));

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }
}