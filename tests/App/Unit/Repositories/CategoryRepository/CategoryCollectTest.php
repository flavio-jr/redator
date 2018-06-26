<?php

namespace Tests\App\Unit\Repositories\CategoryRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\CategoryDump;
use App\Dumps\DumpsFactories\DumpFactory;
use App\Repositories\CategoryRepository\Collect\CategoryCollection;

class CategoryCollectTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var CategoryDump
     */
    private $categoryDump;

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    /**
     * @var CategoryCollection
     */
    private $categoryCollection;

    public function setUp()
    {
        parent::setUp();

        $this->categoryDump = $this->container->get(CategoryDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
        $this->categoryCollection = $this->container->get(CategoryCollection::class);
    }

    public function testGetCategoriesMustNotBeEmpty()
    {
        $this->dumpFactory->produce($this->categoryDump, 5);
        
        $categories = $this->categoryCollection
            ->getAll();

        $this->assertCount(5, $categories);
    }
}