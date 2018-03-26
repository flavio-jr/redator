<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Application;

class UsersControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    public function testRegisterNewUser()
    {
        $userData = $this->container->get('App\Dumps\UserDump')
            ->make()
            ->toArray();

        $response = $this->post(Application::PREFIX . '/users', $userData);

        $this->assertEquals(200, $response->getStatusCode());
    }
}