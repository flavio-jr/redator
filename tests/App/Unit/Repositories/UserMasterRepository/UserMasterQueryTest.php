<?php

namespace Tests\App\Unit\Repositories\UserMasterRepository;

use Tests\TestCase;
use App\Entities\User;
use App\Services\Persister\PersisterInterface as Persister;
use App\Repositories\UserMasterRepository\Query\UserMasterQuery;
use App\Exceptions\EntityNotFoundException;
use Tests\DatabaseRefreshTable;

class UserMasterQueryTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var User
     */
    private $userEntity;

    /**
     * @var Persister
     */
    private $persister;

    /**
     * @var UserMasterQuery
     */
    private $userMasterQuery;

    public function setUp()
    {
        parent::setUp();

        $this->userEntity = $this->container->get('User');
        $this->persister = $this->container->get('PersisterService');
        $this->userMasterQuery = $this->container->get(UserMasterQuery::class);
    }

    public function testShouldNotFindUserMaster()
    {
        $this->expectException(EntityNotFoundException::class);

        $this->userMasterQuery
            ->getMasterUser();
    }

    public function testShouldFindTheMasterUser()
    {
        $user = $this->userEntity;

        $user->setName('master');
        $user->setUsername('master');
        $user->setPassword('123456');
        $user->setType('M');

        $this->persister->persist($user);

        $user = $this->userMasterQuery
            ->getMasterUser();

        $this->assertNotNull($user);
    }
}