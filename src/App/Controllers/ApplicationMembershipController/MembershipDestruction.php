<?php

namespace App\Controllers\ApplicationMembershipController;

use App\Repositories\ApplicationTeamRepository\Destruction\ApplicationMemberDestructionInterface as ApplicationMemberDestruction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Exceptions\UserNotAllowedToRemoveMemberFromApplication;

final class MembershipDestruction
{
    /**
     * The repository for remove member from application
     * @var ApplicationMemberDestruction
     */
    private $applicationMemberDestruction;

    public function __construct(ApplicationMemberDestruction $applicationMemberDestruction)
    {
        $this->applicationMemberDestruction = $applicationMemberDestruction;
    }

    public function destroy(Request $request, Response $response, array $args)
    {
        try {
            $username = $args['member'];
            $applicationName = $args['app'];

            $this->applicationMemberDestruction
                ->destroy($username, $applicationName);

            $response->getBody()
                ->write('The member was successfully removed');

            return $response->withStatus(200);
        } catch (UserNotAllowedToRemoveMemberFromApplication $e) {
            $response->getBody()
                ->write('The user is not authorized to make this operation');

            return $response->withStatus(403);
        }
    }
}