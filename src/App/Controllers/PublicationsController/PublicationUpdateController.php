<?php

namespace App\Controllers\PublicationsController;

use App\Repositories\PublicationRepository\Update\PublicationUpdateInterface as PublicationUpdate;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class PublicationUpdateController
{
    /**
     * The publication update repository
     * @var PublicationUpdate
     */
    private $publicationUpdate;

    public function __construct(PublicationUpdate $publicationUpdate)
    {
        $this->publicationUpdate = $publicationUpdate;
    }

    public function update(Request $request, Response $response, array $args)
    {
        $publicationUpdated = $this->publicationUpdate
            ->update(
                $args['publication'],
                $args['app'],
                $request->getParsedBody()
            );

        if ($publicationUpdated) {
            $response->getBody()
                ->write('The publication was successfully updated');

            return $response->withStatus(201);
        }

        $response->getBody()
            ->write('The publication could not be updated');

        return $response->withStatus(400);
    }
}