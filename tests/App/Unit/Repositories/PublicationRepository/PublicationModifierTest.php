<?php

namespace Tests\App\Unit\Repositories\UserRepository;

use Tests\TestCase;
use Tests\DatabaseRefreshTable;
use App\Dumps\ApplicationDump;
use App\Dumps\PublicationDump;
use App\Repositories\PublicationRepository\Modify\PublicationModifier;
use App\Services\Player;
use App\Dumps\UserDump;
use App\Entities\Publication;
use App\Exceptions\WrongEnumTypeException;

class PublicationModifierTest extends TestCase
{
    use DatabaseRefreshTable;

    /**
     * @var UserDump
     */
    private $userDump;

    /**
     * @var ApplicationDump
     */
    private $applicationDump;

    /**
     * @var PublicationDump
     */
    private $publicationDump;

    /**
     * @var PublicationModifier
     */
    private $publicationModifier;

    public function setUp()
    {
        parent::setUp();

        $this->userDump = $this
            ->container
            ->get(UserDump::class);

        $this->applicationDump = $this
            ->container
            ->get(ApplicationDump::class);

        $this->publicationDump = $this
            ->container
            ->get(PublicationDump::class);

        $this->publicationModifier = $this
            ->container
            ->get(PublicationModifier::class);
    }

    private function getPublication(): Publication
    {
        $user = $this
            ->userDump
            ->create(['type' => 'P']);

        $application = $this
            ->applicationDump
            ->create(['owner' => $user]);

        $publication = $this
            ->publicationDump
            ->create(['application' => $application]);

        Player::setPlayer($user);
        
        return $publication;
    }

    public function testPublicationMustHaveTheStatusChanged()
    {
        $publication = $this->getPublication();

        $this
            ->publicationModifier
            ->modify(
                $publication->getSlug(),
                $publication->getApplication()->getSlug(),
                ['status' => 'DF']
            );

        $this->assertDatabaseHaveWith(
            $publication->getId(),
            $publication,
            ['status' => 'DF']
        );
    }

    public function testWrongPublicationStatusMustRiseException()
    {
        $publication = $this->getPublication();

        $this->expectException(WrongEnumTypeException::class);

        $this
            ->publicationModifier
            ->modify(
                $publication->getSlug(),
                $publication->getApplication()->getSlug(),
                ['status' => 'NON_EXISTENT']
            );
    }
}
