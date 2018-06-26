<?php

namespace Tests\App\Integration\Controllers\CategoriesController;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Dumps\CategoryDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class CategoryDestructionControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var CategoryDump
     */
    private $categoryDump;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->categoryDump = $this->container->get(CategoryDump::class);
    }

    public function testMustReturnHttpOkForAuthorizedUser()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $category = $this->categoryDump->create();

        Player::setPlayer($user);

        $response = $this->delete(Application::PREFIX . "/categories/{$category->getSlug()}");

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustReturnHttpForbiddenForUnauthorizedUser()
    {
        $user = $this->userDump->create();
        $category = $this->categoryDump->create();

        Player::setPlayer($user);

        $response = $this->delete(Application::PREFIX . "/categories/{$category->getSlug()}");

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testMustReturnHttpNotFoundForUnexistentCategory()
    {
        $user = $this->userDump->create(['type' => 'P']);

        Player::setPlayer($user);

        $response = $this->delete(Application::PREFIX . "/categories/news");

        $this->assertEquals(404, $response->getStatusCode());
    }
}