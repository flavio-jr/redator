<?php

namespace App\Containers;

use Slim\Container;
use App\Repositories\UserRepository;
use App\Services\Persister;
use App\Repositories\ApplicationRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\PublicationRepository;
use App\Entities\User;
use App\Entities\Application;

class RepositoriesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserRepository'] = function (Container $c) use ($config) {
            $em = $c->get('doctrine')->getEntityManager();
            return new UserRepository(new User(), $em, new Persister($em));
        };

        $container['ApplicationRepository'] = function (Container $c) use ($config) {
            $em = $c->get('doctrine')->getEntityManager();
            $persister = $c->get('PersisterService');
            $application = new Application();

            return new ApplicationRepository($application, $em, $persister);
        };

        $container['CategoryRepository'] = function ($c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new CategoryRepository($em, $c->get('PersisterService'));
        };

        $container['PublicationRepository'] = function ($c) {
            $em = $c->get('doctrine')->getEntityManager();
            $persisterService = $c->get('PersisterService');
            $applicationRepository = $c->get('ApplicationRepository');
            $categoryRepository = $c->get('CategoryRepository');
            $htmlSanitizer = $c->get('HtmlSanitizer');

            return new PublicationRepository(
                $em,
                $persisterService,
                $applicationRepository,
                $categoryRepository,
                $htmlSanitizer
            );
        };
    }
}