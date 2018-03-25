<?php

namespace Tests\App\Integration\Controllers;

use Tests\TestCase;
use App\Application;
use Tests\DatabaseRefreshTable;

class LoginControllerTest extends TestCase
{
    use DatabaseRefreshTable;

    private $username = 'bulbasaur';
    private $password = 'bulba';

    public function setUp()
    {
        parent::setUp();

        $this->container
            ->get('App\Dumps\UserDump')
            ->create([
                'username' => $this->username,
                'password' => $this->password
            ]);      
    }

    public function testShouldGiveHttpOkForRightCredentials()
    {
        $response = $this->post(Application::PREFIX . '/login', [
            'username' => $this->username,
            'password' => $this->password
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testShouldReturnBadRequestForInvalidUser()
    {
        $response = $this->post(Application::PREFIX . '/login', [
            'username' => 'squirtle',
            'password' => $this->password
        ]);

        $this->assertEquals(403, $response->getStatusCode());
    }
}