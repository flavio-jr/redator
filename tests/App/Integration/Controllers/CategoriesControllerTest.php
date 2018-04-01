<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use App\Dumps\CategoryDump;
use App\Application;
use Tests\DatabaseRefreshTable;

class CategoriesControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * The category dump
     * @var CategoryDump
     */
    private $categoryDump;

    public function setUp()
    {
        parent::setUp();

        $this->categoryDump = $this->container->get('App\Dumps\CategoryDump');
    }

    public function testMustReturnHttpOkForCreatingNewCategory()
    {
        $data = $this->categoryDump->make()->toArray();

        $response = $this->post(Application::PREFIX . '/categories', $data);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testMustNotRegisterCategoryWithExistentUsername()
    {
        $category = $this->categoryDump->create();

        $data = $this->categoryDump->make(['name' => $category->getName()])->toArray();

        $response = $this->post(Application::PREFIX . '/categories', $data);

        $this->assertEquals(412, $response->getStatusCode());
    }
}