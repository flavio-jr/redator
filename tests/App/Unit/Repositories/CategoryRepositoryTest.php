<?php

namespace Tests\App\Unit\Repositories;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;

class CategoryRepositoryTest extends TestCase
{
    use DatabaseRefreshTable;

    private $categoryRepository;
    private $categoryDump;

    public function setUp()
    {
        parent::setUp();

        $this->categoryRepository = $this->container->get('CategoryRepository');
        $this->categoryDump = $this->container->get('App\Dumps\CategoryDump');
    }

    public function testCreateNewCategory()
    {
        $categoryData = $this->categoryDump->make()->toArray();

        $category = $this->categoryRepository->create($categoryData);

        $this->assertDatabaseHave($category);
    }
}