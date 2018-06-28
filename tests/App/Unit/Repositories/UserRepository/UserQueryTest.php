<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Repositories\UserRepository\Query\UserQuery;
use App\Dumps\UserDump;

class UserQueryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserQuery
     */
    private $userQuery;

    /**
     * @var UserDump
     */
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userQuery = $this->container->get(UserQuery::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    public function testShouldReturnUserWithGivenUsername()
    {
        $user = $this->userDump->create();

        $userFinded = $this->userQuery->findByUsername($user->getUsername());

        $this->assertNotNull($userFinded);
    }
}