<?php

namespace Tests\App\Integration\Controllers\CategoriesController;

use Tests\TestCase;
use App\Dumps\CategoryDump;
use App\Application;
use Tests\DatabaseRefreshTable;

class CategoryStoreControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var CategoryDump
     */
    private $categoryDump;

    public function setUp()
    {
        parent::setUp();

        $this->categoryDump = $this->container->get(CategoryDump::class);
    }

    public function testCategoryStoreMustReturnHttpCreated()
    {
        $data = $this->categoryDump->make()->toArray();

        $response = $this->post(Application::PREFIX . '/categories', $data);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testCategoryStoreWithExistentNameMustReturnHttpPreConditionFailed()
    {
        $category = $this->categoryDump->create();

        $data = $this->categoryDump->make(['name' => $category->getName()])->toArray();

        $response = $this->post(Application::PREFIX . '/categories', $data);

        $this->assertEquals(412, $response->getStatusCode());
    }
}