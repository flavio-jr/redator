<?php

namespace App\Controllers\PublicationsController;

use App\Repositories\PublicationRepository\Destruction\PublicationDestructionInterface as PublicationDestruction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class PublicationDestructionController
{
    /**
     * The publication destruction repository
     * @var PublicationDestruction
     */
    private $publicationDestruction;

    public function __construct(PublicationDestruction $publicationDestruction)
    {
        $this->publicationDestruction = $publicationDestruction;
    }

    public function destroy(Request $request, Response $response, array $args)
    {
        $publicationDestructed = $this->publicationDestruction
            ->destroy(
                $args['publication'],
                $args['app']
            );

        if ($publicationDestructed) {
            $response->getBody()
                ->write('Publication successfully deleted');
            
            return $response->withStatus(200);
        }

        $response->getBody()
            ->write('The publication could not be deleted');

        return $response->withStatus(400);
    }
}