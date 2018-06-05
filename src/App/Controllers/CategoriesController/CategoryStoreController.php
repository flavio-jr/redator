<?php

namespace App\Controllers\CategoriesController;

use App\Repositories\CategoryRepository\Store\CategoryStoreInterface as CategoryStore;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class CategoryStoreController
{
    /**
     * The category store repository
     * @var CategoryStore
     */
    private $categoryStore;

    public function __construct(CategoryStore $categoryStore)
    {
        $this->categoryStore = $categoryStore;
    }

    public function store(Request $request, Response $response)
    {
        try {
            $category = $this->categoryStore->store($request->getParsedBody());

            $response
                ->getBody()
                ->write(json_encode([
                    'category' => $category->toArray()
                ]));

            return $response->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            $response->getBody()->write('The category name already exists');

            return $response->withStatus(412);
        }
    }
}