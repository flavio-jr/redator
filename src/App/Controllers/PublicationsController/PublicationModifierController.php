<?php

namespace App\Controllers\PublicationsController;

use App\Repositories\PublicationRepository\Modify\PublicationModifierInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\WrongEnumTypeException;
use App\Exceptions\EntityNotFoundException;

final class PublicationModifierController
{
    /**
     * @var PublicationModifierInterface
     */
    private $publicationModifier;

    public function __construct(PublicationModifierInterface $publicationModifier)
    {
        $this->publicationModifier = $publicationModifier;
    }

    public function modify(Request $request, Response $response, array $args)
    {
        try {
            $this
                ->publicationModifier
                ->modify(
                    $args['publication'],
                    $args['app'],
                    $request->getParsedBody()
                );

            $response
                ->getBody()
                ->write('The publication was modified');

            return $response->withStatus(204);
        } catch (EntityNotFoundException $e) {
            $response
                ->getBody()
                ->write("The {$e->getEntityName()} could not be found");

            return $response->withStatus(404);
        }
    }
}
