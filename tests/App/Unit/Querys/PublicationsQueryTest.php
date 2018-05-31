<?php

namespace Tests\App\Unit\Querys;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\PublicationDump;
use App\Dumps\DumpsFactories\DumpFactory;
use App\Querys\Publications\PublicationQuery;
use App\Dumps\ApplicationDump;

class PublicationsQueryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var PublicationQuery
     */
    private $publicationsQuery;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->publicationsQuery = $this->container->get(PublicationQuery::class);
        $this->publicationDump = $this->container->get(PublicationDump::class);
        $this->applicationDump = $this->container->get(ApplicationDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testPublicationsQueryShouldBeNotEmpty()
    {
        $application = $this->applicationDump->create();

        $this->dumpFactory
            ->produce(
                $this->publicationDump,
                5,
                ['application' => $application]
            );

        $results = $this->publicationsQuery
            ->get($application);

        $this->assertCount(5, $results);
    }

    public function testQueryFilterByTitle()
    {
        $application = $this->applicationDump->create();

        $publications = $this->dumpFactory
            ->produce(
                $this->publicationDump,
                5,
                ['application' => $application]
            );

        $firstItem = $publications[0];

        $results = $this->publicationsQuery
            ->get($application, ['title' => $firstItem->getTitle()]);

        $itemsWithCorrectRegex = array_filter($results, function ($item) use ($firstItem) {
            $replaceSubject = ['.', '\'', '?'];
            $replace = ['\.', '\\\'', '\?'];

            $titleResult = str_replace($replaceSubject, $replace, $item['title']);

            return preg_match_all('/[\w\s][' . $titleResult . '][\w\s]*/', $firstItem->getTitle());
        });

        $this->assertCount(count($itemsWithCorrectRegex), $results);
    }

    public function testQueryFilterByCategory()
    {
        $application = $this->applicationDump->create();

        $publications = $this->dumpFactory
            ->produce(
                $this->publicationDump,
                5,
                ['application' => $application]
            );

        $firstItem = $publications[0];

        $results = $this->publicationsQuery
            ->get($application, ['category' => $firstItem->getCategory()->getSlug()]);

        $itemsWithCorrectCategory = array_filter($results, function ($item) use ($firstItem) {
            return $item['category']['slug'] === $firstItem->getCategory()->getSlug();
        });

        $this->assertCount(count($itemsWithCorrectCategory), $results);
    }
}