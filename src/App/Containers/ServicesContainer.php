<?php

namespace App\Containers;

use Slim\Container;
use App\Services\Player;
use App\Services\Slugify\Slugify;
use Cocur\Slugify\Slugify as Slugifier;
use App\Repositories\UserRepository\Finder\UserFinder;
use App\Services\Persister\Persister;
use App\Services\HtmlSanitizer\HtmlSanitizer;
use App\Services\UserSession\UserSession;

class ServicesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserSession'] = function ($c) {
            return new UserSession();
        };

        $container['Player'] = function ($c) {
            return new Player($c->get(UserFinder::class));
        };

        $container['PersisterService'] = function ($c) {
            return new Persister($c->get('doctrine')->getEntityManager());
        };

        $container['HtmlSanitizer'] = function ($c) {
            $htmlPurifyConfig = \HTMLPurifier_Config::createDefault();

            return new HtmlSanitizer(new \HTMLPurifier($htmlPurifyConfig));
        };

        $container[Slugify::class] = function (Container $c) {
            $slugifier = new Slugifier();

            return new Slugify($slugifier);
        };
    }
}