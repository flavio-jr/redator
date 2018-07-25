<?php

namespace App\Controllers\ApplicationMembershipController;

use App\Repositories\ApplicationTeamRepository\Store\ApplicationTeamStoreInterface as ApplicationTeamStore;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\UserNotAllowedToAddMemberToApplication;

final class MembershipStore
{
    /**
     * The repository for add member to application team
     * @var ApplicationTeamStore
     */
    private $applicationTeamStore;

    public function __construct(ApplicationTeamStore $applicationTeamStore)
    {
        $this->applicationTeamStore = $applicationTeamStore;
    }

    public function store(Request $request, Response $response, array $args)
    {
        try {
            $newMemberUsername = $request->getParsedBody()['member'];
            $applicationName = $args['app'];

            $this->applicationTeamStore
                ->store($newMemberUsername, $applicationName);

            $response
                ->getBody()
                ->write('The user was successfully added as an member to the app');

            return $response->withStatus(200);
        } catch (UserNotAllowedToAddMemberToApplication $e) {
            $response->getBody()
                ->write('The user is not allowed to do the operation');

            return $response->withStatus(403);
        }
    }
}