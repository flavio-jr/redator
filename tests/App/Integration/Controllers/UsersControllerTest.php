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

    public function testRegisterUserWithExistentUsername()
    {
        $userData = $this->container->get('App\Dumps\UserDump')
            ->make()
            ->toArray();
        
        $firstResponse = $this->post(Application::PREFIX . '/users', $userData);
        $response = $this->post(Application::PREFIX . '/users', $userData);

        $this->assertEquals(412, $response->getStatusCode());
    }
}