<?php

namespace App\Controllers;

use App\Repositories\PublicationRepository;
use Slim\Http\Request;
use Slim\Http\Response;

final class PublicationsController
{
    private $publicationRepository;

    public function __construct(PublicationRepository $publicationRepository)
    {
        $this->publicationRepository = $publicationRepository;
    }

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
}