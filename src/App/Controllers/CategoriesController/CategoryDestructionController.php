<?php

namespace App\Controllers\CategoriesController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\CategoryRepository\Destruction\CategoryDestructionInterface as CategoryDestruction;
use App\Exceptions\UserNotAllowedException;
use App\Exceptions\EntityNotFoundException;

final class CategoryDestructionController
{
    /**
     * The category destruction repository
     * @var CategoryDestruction
     */
    private $categoryDestruction;

    public function __construct(CategoryDestruction $categoryDestruction)
    {
        $this->categoryDestruction = $categoryDestruction;
    }

    public function destroy(Request $request, Response $response, array $args)
    {
        try {
            $this->categoryDestruction
                ->destroy($args['category']);

            $response
                ->getBody()
                ->write('The category was successfully destroyed');

            return $response->withStatus(200);
        } catch (UserNotAllowedException $e) {
            $response
                ->getBody()
                ->write('The user is not allowed to destroy the category');

            return $response->withStatus(403);
        } catch (EntityNotFoundException $e) {
            $response
                ->getBody()
                ->write('The category was not found');

            return $response->withStatus(404);
        }
    }
}