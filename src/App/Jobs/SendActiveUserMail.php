<?php

namespace App\Jobs;

use Slim\Container;
use App\Services\Mailers\HTMLMailer;

class SendActiveUserMail implements JobInterface
{
    /**
     * The html mailer
     * @var HTMLMailer
     */
    private $mailer;

    public function __construct(Container $container)
    {
        $this->mailer = $container->get('HTMLMailer');
    }

    public function handle(array $params)
    {
        return $this->mailer
            ->from($params['from'])
            ->to($params['to'])
            ->subject('Confirmação de cadastro')
            ->body($params['body'])
            ->send();
    }
}