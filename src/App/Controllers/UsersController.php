<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Exceptions\UniqueFieldException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Services\Player;
use App\Services\TemplateEngines\TemplateEngineInterface;
use App\Services\Mailers\MailerInterface;
use App\Services\QueueWorkers\FPMWorker;

final class UsersController
{
    /**
     * The user repository
     * @var UserRepository
     */
    private $userRepository;

    /**
     * The template engine
     * @var TemplateEngineInterface
     */
    private $templateEngine;

    /**
     * The async worker
     * @var FPMWorker
     */
    private $worker;

    public function __construct(
        UserRepository $userRepository,
        TemplateEngineInterface $templateEngine,
        FPMWorker $worker
    ) {
        $this->userRepository = $userRepository;
        $this->templateEngine = $templateEngine;
        $this->worker = $worker;
    }

    /**
     * Register a new user
     * @method store
     * @param Request $request
     * @param Response $response
     */
    public function store(Request $request, Response $response)
    {
        try {
            $data = $request->getParsedBody();

            $this->userRepository->create($data);

            return $response->write('User sucessfully created')->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Username already taken')->withStatus(412);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }

    /**
     * Updates an user
     * @method update
     * @param Request $request
     * @param Response $response
     */
    public function update(Request $request, Response $response, array $args)
    {
        try {
            $data = $request->getParsedBody();

            $this->userRepository->update($args['user_id'], $data);

            return $response->write('User data sucessfully updated')->withStatus(200);
        } catch (UniqueConstraintViolationException $e) {
            return $response->write('Username already taken')->withStatus(412);
        } catch (\Exception $e) {
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }

    /**
     * Checks for username availability
     * @method usernameAvailaibility
     * @param Request $request
     * @param Response $response
     * @param array $args
     */
    public function usernameAvailaibility(Request $request, Response $response, array $args)
    {
        $userNameAvailaibility = $this->userRepository->isUsernameAvailable($args['username']);

        return $response
            ->write(json_encode([
                'available' => (int) $userNameAvailaibility
            ]))
            ->withStatus(200);
    }

    /**
     * Send email to unactive user
     * @method mailUnactiveUser
     * @param Request $request
     * @param Response $response
     */
    public function mailUnactiveUser(Request $request, Response $response)
    {
        try {
            $user = Player::user();

            if (!$this->userRepository->isUnactiveUser($user)) {
                return $response->write('User already active')->withStatus(403);
            }

            $this->templateEngine->setParams([
                'name' => $user->getName(),
                'url'  => $request->getParam('url')
            ]);

            $emailTemplate = $this->templateEngine->render('emails/user-register-confirmation');
            
            $this->worker->fire('App\Jobs\SendActiveUserMail', [
                'from'    => getenv('APP_MAIL'),
                'to'      => $user->getEmail(),
                'subject' => 'Confirmação de cadastro',
                'body'    => $emailTemplate
            ]);

            return $response->write('Confirmation email sent')->withStatus(200);
        } catch (\Exception $e) {
            echo $e->getMessage();
            if (getenv('APP_ENV') === 'DEV') {
                return $response->write($e->getMessage())->withStatus(500);
            }

            return $response->write('An exception ocurred')->withStatus(500);
        }
    }
}