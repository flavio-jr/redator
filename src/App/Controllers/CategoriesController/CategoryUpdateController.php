<?php

namespace App\Controllers\CategoriesController;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\CategoryRepository\Update\CategoryUpdateInterface as CategoryUpdate;
use App\Exceptions\UserNotAllowedException;
use App\Exceptions\EntityNotFoundException;

final class CategoryUpdateController
{
    /**
     * The category update repository
     * @var CategoryUpdate
     */
    private $categoryUpdate;

    public function __construct(CategoryUpdate $categoryUpdate)
    {
        $this->categoryUpdate = $categoryUpdate;
    }

    public function update(Request $request, Response $response, array $args)
    {
        try {
            $this->categoryUpdate
                ->update($args['category'], $request->getParsedBody());

            $response
                ->getBody()
                ->write('The category was successfully updated');

            return $response->withStatus(200);
        } catch (UserNotAllowedException $e) {
            $response
                ->getBody()
                ->write('The user are not allowed to update categories');

            return $response->withStatus(403);
        } catch (EntityNotFoundException $e) {
            $response
                ->getBody()
                ->write('The category could not be found');

            return $response->withStatus(404);
        }
    }
}