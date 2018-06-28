<?php

namespace Tests\App\Unit\Repositories\CategoryRepository;

use Tests\TestCase;
use App\Dumps\CategoryDump;
use App\Dumps\UserDump;
use App\Services\Player;
use App\Repositories\CategoryRepository\Destruction\CategoryDestruction;
use App\Exceptions\UserNotAllowedException;
use Tests\DatabaseRefreshTable;

class CategoryDestructionTest extends TestCase
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
     * @var CategoryDestruction
     */
    private $categoryDestruction;

    public function setUp()
    {
        parent::setUp();

        $this->categoryDump = $this->container->get(CategoryDump::class);
        $this->userDump = $this->container->get(UserDump::class);
        $this->categoryDestruction = $this->container->get(CategoryDestruction::class);
    }

    public function testPartnerUserMustBeCapableOfDestroyCategory()
    {
        $partnerUser = $this->userDump->create(['type' => 'P']);
        $category = $this->categoryDump->create();

        Player::setPlayer($partnerUser);

        $this->categoryDestruction
            ->destroy($category->getSlug());

        $this->assertTrue(true); // No exception is thrown
    }

    public function testWritterUserMustNotBeCapableOfDestroyCategory()
    {
        $partnerUser = $this->userDump->create();
        $category = $this->categoryDump->create();

        Player::setPlayer($partnerUser);

        $this->expectException(UserNotAllowedException::class);

        $this->categoryDestruction
            ->destroy($category->getSlug());
    }
}