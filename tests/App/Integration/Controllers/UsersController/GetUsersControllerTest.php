<?php

namespace Tests\App\Integration\Controllers\UsersController;

use Tests\TestCase;
use App\Dumps\DumpsFactories\DumpFactory;
use App\Dumps\UserDump;
use App\Services\Player;
use Tests\DatabaseRefreshTable;
use App\Application;

class GetUsersControllerTest extends TestCase
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

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this->container->get(UserDump::class);
        $this->dumpFactory = $this->container->get('DumpFactory');
    }

    public function testMasterUserMustReceiveHttpOkForGetUsers()
    {
        $master = $this->userDump->create(['type' => 'M']);

        $this->dumpFactory->produce($this->userDump, 5);

        Player::setPlayer($master);

        $response = $this->get(Application::PREFIX . '/users');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNotMasterUserMustReceiveHttpForbidden()
    {
        $user = $this->userDump->create();
        
        Player::setPlayer($user);

        $response = $this->get(Application::PREFIX . '/users');

        $this->assertEquals(403, $response->getStatusCode());
    }
}
