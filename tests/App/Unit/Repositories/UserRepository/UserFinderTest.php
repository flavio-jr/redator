<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use App\Repositories\UserRepository\Finder\UserFinder;
use App\Dumps\UserDump;
use App\Exceptions\EntityNotFoundException;
use Tests\DatabaseRefreshTable;

class UserFinderTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserFinder
     */
    private $userFinder;

    /**
     * @var UserDump
     */
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userFinder = $this->container->get(UserFinder::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testFindUserByIdMustNotThrownException()
    {
        $user = $this->userDump->create();

        $userFinding = $this->userFinder
            ->find($user->getId());

        // Does not throw exception
        $this->assertTrue(true);
    }

    public function testFindUserMustThrowException()
    {
        $this->expectException(EntityNotFoundException::class);

        $userFinding = $this->userFinder
            ->find('fakeId');
    }
}