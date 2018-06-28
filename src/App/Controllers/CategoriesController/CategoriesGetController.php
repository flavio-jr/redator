<?php

namespace App\Controllers\CategoriesController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\CategoryRepository\Collect\CategoryCollectionInterface as CategoryCollection;

final class CategoriesGetController
{
    /**
     * The category collection repository
     * @var CategoryCollection
     */
    private $categoryCollection;

    public function __construct(CategoryCollection $categoryCollection)
    {
        $this->categoryCollection = $categoryCollection;
    }

    public function get(Request $request, Response $response, array $args)
    {
        $categories = $this->categoryCollection
            ->getAll($request->getParsedBody());

        $response
            ->getBody()
            ->write(json_encode(['categories' => $categories]));

        return $response->withStatus(200);
    }
}
