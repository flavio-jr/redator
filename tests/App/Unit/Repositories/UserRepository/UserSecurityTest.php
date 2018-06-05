<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use App\Dumps\UserDump;
use App\Repositories\UserRepository\Security\UserSecurity;
use App\Exceptions\WrongCredentialsException;
use Tests\DatabaseRefreshTable;

class UserSecurityTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserSecurity
     */
    private $userSecurity;

    /**
     * @var UserDump
     */
    private $userDump;

    public function setUp()
    {
        parent::setUp();

        $this->userSecurity = $this->container->get(UserSecurity::class);
        $this->userDump = $this->container->get(UserDump::class);
    }

    /**
     * This test can throw WrongCredentialsException
     */
    public function testCorrectUserMustReturnJWT()
    {
        $pass = 'HODOR';
        $user = $this->userDump->create(['password' => $pass]);

        $jwt = $this->userSecurity
            ->getAccessToken($user->getUsername(), $pass);

        // No exception thrown
        $this->assertTrue(true);
    }

    public function testWrongUserInfoMustThrowException()
    {
        $pass = 'HODOR';
        $user = $this->userDump->create(['password' => $pass]);

        $this->expectException(WrongCredentialsException::class);

        $jwt = $this->userSecurity
            ->getAccessToken($user->getUsername(), strrev($pass));
    }
}