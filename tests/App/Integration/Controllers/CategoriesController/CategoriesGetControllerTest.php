<?php

namespace Tests\App\Integration\Controllers\CategoriesController;

use Tests\TestCase;
use App\Dumps\CategoryDump;
use App\Dumps\DumpsFactories\DumpFactory;
use Tests\DatabaseRefreshTable;
use App\Services\Player;
use App\Dumps\UserDump;
use App\Application;

class CategoriesGetControllerTest extends TestCase
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

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->categoryDump = $this->container->get(CategoryDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testGetAppsMustReturnHttpOk()
    {
        $this->dumpFactory->produce($this->categoryDump, 5);

        Player::setPlayer($this->userDump->create());

        $response = $this->get(Application::PREFIX . '/categories');

        $this->assertEquals(200, $response->getStatusCode());
    }
}