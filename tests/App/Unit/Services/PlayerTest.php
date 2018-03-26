<?php

namespace Tests\App\Unit\Services;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Services\Player;

class PlayerTest extends TestCase
{
    use DatabaseRefreshTable;

    private $userSession;
    private $player;

    public function setUp()
    {
        parent::setUp();

        $this->userSession = $this->container->get('UserSession');
        $this->player = $this->container->get('Player');
    }

    public function testShouldSetUserWithToken()
    {
        $user = $this->container->get('App\Dumps\UserDump')->create();

        $jwt = $this->userSession->createNewToken($user->getId());

        $this->player->setPlayerFromToken($jwt);

        $this->assertNotNull(Player::user());
    }
}