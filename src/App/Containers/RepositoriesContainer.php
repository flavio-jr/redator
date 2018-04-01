<?php

namespace App\Containers;

use Slim\Container;
use App\Repositories\UserRepository;
use App\Services\Persister;
use App\Repositories\ApplicationRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\PublicationRepository;

class RepositoriesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserRepository'] = function ($c) use ($config) {
            $em = $c->get('doctrine')->getEntityManager();
            return new UserRepository($em, new Persister($em));
        };

        $container['ApplicationRepository'] = function ($c) use ($config) {
            $em = $c->get('doctrine')->getEntityManager();
            return new ApplicationRepository($em, new Persister($em));
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