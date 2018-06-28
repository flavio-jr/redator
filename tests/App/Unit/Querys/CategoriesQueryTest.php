<?php

namespace Tests\App\Unit\Querys;

use Tests\TestCase;
use App\Querys\Categories\CategoriesQuery;
use App\Dumps\CategoryDump;
use App\Dumps\DumpsFactories\DumpFactory;
use Tests\DatabaseRefreshTable;

class CategoriesQueryTest extends TestCase
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
     * @var CategoriesQuery
     */
    private $categoriesQuery;

    public function setUp()
    {
        parent::setUp();

        $this->categoriesQuery = $this->container->get(CategoriesQuery::class);
        $this->categoryDump = $this->container->get(CategoryDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testGetAllCategoriesMustNotBeEmpty()
    {
        $this->dumpFactory->produce($this->categoryDump, 5);

        $categories = $this->categoriesQuery
            ->get();

        $this->assertCount(5, $categories);
    }

    public function testGetCategoriesWithNameFilterMustReturnOnlyResultsInPattern()
    {
        $categoriesProduced = $this->dumpFactory->produce($this->categoryDump, 5);
        $firstCategory = $categoriesProduced[0];

        $results = $this->categoriesQuery
            ->get(['name' => $firstCategory->getName()]);

        $itemsWithCorrectRegex = array_filter($results, function ($item) use ($firstCategory) {
            $regex = '/[^A-Za-z0-9]/g';

            $subject = str_replace($regex, '', $item['name']);
            $search = str_replace($regex, '', $firstCategory->getName());

            return preg_match_all('/[\w\s][' . $subject . '][\w\s]*/', $search);
        });

        $this->assertCount(count($itemsWithCorrectRegex), $results);
    }
}