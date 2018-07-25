<?php

namespace App\Controllers\PublicationsController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\PublicationRepository\Finder\PublicationFinderInterface as PublicationFinder;

final class PublicationGetController
{
    /**
     * The publication finder repository
     * @var PublicationFinder
     */
    private $publicationFinder;

    public function __construct(PublicationFinder $publicationFinder)
    {
        $this->publicationFinder = $publicationFinder;
    }

    public function get(Request $request, Response $response, array $args)
    {
        $publication = $this->publicationFinder
            ->find($args['publication'], $args['app']);

        if ($publication) {
            $response
                ->getBody()
                ->write(json_encode(['publication' => $publication->toArray()]));

            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        }

        $response
            ->getBody()
            ->write('The publication could not be found');

        return $response->withStatus(404);
    }
}