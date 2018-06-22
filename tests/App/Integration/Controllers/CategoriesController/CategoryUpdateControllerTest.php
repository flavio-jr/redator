<?php

namespace Tests\App\Integration\Controllers\CategoriesController;

use Tests\TestCase;
use App\Dumps\CategoryDump;
use App\Dumps\UserDump;
use App\Services\Player;
use App\Application;
use Tests\DatabaseRefreshTable;

class CategoryUpdateControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var CategoryDump
     */
    private $categoryDump;

    /**
     * @var UserDump
     */
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->categoryDump = $this->container->get(CategoryDump::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testUpdateCategoryWithPartnerUserMustReturnHttpOk()
    {
        $user = $this->userDump->create(['type' => 'P']);
        $category = $this->categoryDump->create();
        $data = $this->categoryDump->make()->toArray();

        Player::setPlayer($user);

        $response = $this->put(Application::PREFIX . "/categories/{$category->getSlug()}", $data);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testUnauthorizedUserMustReceiveHttpForbidden()
    {
        $user = $this->userDump->create();
        $category = $this->categoryDump->create();
        $data = $this->categoryDump->make()->toArray();

        Player::setPlayer($user);

        $response = $this->put(Application::PREFIX . "/categories/{$category->getSlug()}", $data);

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testNotFoundCategoryMustReturnHttpNotFound()
    {
        $user = $this->userDump->create(['type' => 'P']);

        Player::setPlayer($user);

        $response = $this->put(Application::PREFIX . "/categories/for-dumbies", ['name' => 'For dumbies']);

        $this->assertEquals(404, $response->getStatusCode());
    }
}