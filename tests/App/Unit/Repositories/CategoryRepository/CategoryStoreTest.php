<?php

namespace Tests\App\Unit\Repositories\CategoryRepository;

use Tests\TestCase;
use App\Dumps\CategoryDump;
use App\Repositories\CategoryRepository\Store\CategoryStore;
use Tests\DatabaseRefreshTable;

class CategoryStoreTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var CategoryDump
     */
    private $categoryDump;
    
    /**
     * @var CategoryStore
     */
    private $categoryStore;

    public function setUp()
    {
        parent::setUp();
        
        $this->categoryDump = $this->container->get(CategoryDump::class);
        $this->categoryStore = $this->container->get(CategoryStore::class);
    }

    public function testStoreCategoryShouldPersistData()
    {
        $data = $this->categoryDump->make()->toArray();

        $category = $this->categoryStore->store($data);

        $this->assertDatabaseHave($category);
    }

    public function testStoredCategoryMustHaveSlug()
    {
        $data = $this->categoryDump->make()->toArray();

        $category = $this->categoryStore->store($data);

        $this->assertNotEmpty($category->getSlug());
    }
}