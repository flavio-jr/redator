<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\UserDump;
use App\Dumps\DumpsFactories\DumpFactory;
use App\Services\Player;
use App\Repositories\UserRepository\Collection\UserCollection;
use App\Exceptions\UserNotAllowedException;

class UserCollectionTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var DumpFactory
     */
    private $dumpFactory;

    /**
     * @var UserCollection
     */
    private $userCollection;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
        $this->userCollection = $this->container->get(UserCollection::class);
    }

    public function testMustRetrieveAllUsersForMasterUser()
    {
        $user = $this->userDump->create(['type' => 'M']);
        $this->dumpFactory->produce($this->userDump, 5);

        Player::setPlayer($user);

        $this->assertCount(5, $this->userCollection->getAll());
    }

    public function testMustThrownExceptionForNoMasterUser()
    {
        $user = $this->userDump->create();

        Player::setPlayer($user);

        $this->expectException(UserNotAllowedException::class);

        $this->userCollection->getAll();
    }
}
