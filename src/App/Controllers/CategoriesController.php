<?php

namespace App\Controllers;

use App\Repositories\CategoryRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final class CategoriesController
{
    /**
     * The category repository
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $this->categoryRepository->create($data);

            return $response->write('Category successfully created')->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Category name already taken')->withStatus(412);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') == 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }
}