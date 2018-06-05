<?php

namespace Tests\App\Unit\Repositories\CategoryRepository;

use Tests\TestCase;
use App\Repositories\CategoryRepository\Query\CategoryQuery;
use App\Dumps\CategoryDump;
use Tests\DatabaseRefreshTable;

class CategoryQueryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var CategoryQuery
     */
    private $categoryQuery;

    /**
     * @var CategoryDump
     */
    private $categoryDump;

    public function setUp()
    {
        parent::setUp();

        $this->categoryQuery = $this->container->get(CategoryQuery::class);
        $this->categoryDump = $this->container->get(CategoryDump::class);
    }

    public function testGetCategoryByNameMustReturnNonNull()
    {
        $category = $this->categoryDump->create();

        $categorySearch = $this->categoryQuery->getCategoryByName($category->getSlug());

        $this->assertNotNull($categorySearch);
    }
}