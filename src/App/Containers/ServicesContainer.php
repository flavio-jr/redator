<?php

namespace App\Containers;

use Slim\Container;
use App\Services\UserSession;
use App\Services\Player;
use App\Services\Persister;
use App\Services\HtmlSanitizer;
use PHPMailer\PHPMailer\PHPMailer;
use App\Services\Mailers\HTMLMailer;

class ServicesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserSession'] = function ($c) {
            return new UserSession();
        };

        $container['Player'] = function ($c) {
            return new Player($c->get('UserRepository'));
        };

        $container['PersisterService'] = function ($c) {
            return new Persister($c->get('doctrine')->getEntityManager());
        };

        $container['HtmlSanitizer'] = function ($c) {
            $htmlPurifyConfig = \HTMLPurifier_Config::createDefault();

            return new HtmlSanitizer(new \HTMLPurifier($htmlPurifyConfig));
        };

        $container['HTMLMailer'] = function ($c) {
            $htmlSanitizer = $c->get('HtmlSanitizer');
            $phpMailer = new PHPMailer(true);

            return new HTMLMailer($phpMailer, $htmlSanitizer);
        };
    }
}