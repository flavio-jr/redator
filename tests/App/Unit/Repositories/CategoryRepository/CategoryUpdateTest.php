<?php

namespace Tests\App\Unit\Repositories\CategoryRepository;

use Tests\TestCase;
use App\Dumps\CategoryDump;
use App\Dumps\UserDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Repositories\CategoryRepository\Update\CategoryUpdate;
use App\Exceptions\UserNotAllowedException;

class CategoryUpdateTest extends TestCase
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

    /**
     * @var CategoryUpdate
     */
    private $categoryUpdate;

    public function setUp()
    {
        parent::setUp();

        $this->categoryDump = $this->container->get(CategoryDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->categoryUpdate = $this->container->get(CategoryUpdate::class);
    }

    public function testPartnerUserMustBeCapableOfUpdateCategory()
    {
        $category = $this->categoryDump->create();
        $partnerUser = $this->userDump->create(['type' => 'P']);

        Player::setPlayer($partnerUser);

        $categoryNewData = $this->categoryDump
            ->make()
            ->toArray();

        $this->categoryUpdate
            ->update($category->getSlug(), $categoryNewData);

        $this->assertTrue(true); // No exception is thrown
    }

    public function testWritterUserMustNotBeCapableOfUpdateCategory()
    {
        $category = $this->categoryDump->create();
        $writter = $this->userDump->create();

        Player::setPlayer($writter);

        $this->expectException(UserNotAllowedException::class);

        $this->categoryUpdate
            ->update($category->getSlug(), []);
    }
}