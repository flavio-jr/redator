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
use App\Entities\Category;
use App\Entities\Publication;

class RepositoriesContainer
{
    public function register(Container $container, array $config)
    {
        $container['UserRepository'] = function (Container $c) use ($config) {
            $em = $c->get('doctrine')->getEntityManager();
            $user = $c->get('User');
            $persister = $c->get('PersisterService');

            return new UserRepository($user, $em, $persister);
        };

        $container['ApplicationRepository'] = function (Container $c) use ($config) {
            $em = $c->get('doctrine')->getEntityManager();
            $persister = $c->get('PersisterService');
            $application = $c->get('Application');

            return new ApplicationRepository($application, $em, $persister);
        };

        $container['CategoryRepository'] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();
            $persister = $c->get('PersisterService');
            $category = $c->get('Category');

            return new CategoryRepository($category, $em, $persister);
        };

        $container['PublicationRepository'] = function ($c) {
            $em = $c->get('doctrine')->getEntityManager();
            $persisterService = $c->get('PersisterService');
            $applicationRepository = $c->get('ApplicationRepository');
            $categoryRepository = $c->get('CategoryRepository');
            $htmlSanitizer = $c->get('HtmlSanitizer');
            $publication = $c->get('Publication');

            return new PublicationRepository(
                $publication,
                $em,
                $persisterService,
                $applicationRepository,
                $categoryRepository,
                $htmlSanitizer
            );
        };
    }
}