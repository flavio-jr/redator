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
use App\Repositories\UserRepository\Store\UserStore;
use App\Repositories\UserRepository\Update\UserUpdate;
use App\Repositories\UserRepository\Query\UserQuery;
use App\Repositories\ApplicationRepository\Store\ApplicationStore;
use App\Repositories\ApplicationRepository\Query\ApplicationQuery;
use App\Repositories\ApplicationRepository\Update\ApplicationUpdate;
use App\Repositories\ApplicationRepository\Destruction\ApplicationDestruction;
use App\Services\Slugify\Slugify;

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

        $container[UserStore::class] = function (Container $c) {
            $user = $c->get('User');
            $em = $c->get('doctrine')->getEntityManager();
            $persisterService = $c->get('PersisterService');
            
            return new UserStore($user, $em, $persisterService);
        };

        $container[UserUpdate::class] = function (Container $c) {
            $user = $c->get('User');
            $em = $c->get('doctrine')->getEntityManager();
            $persisterService = $c->get('PersisterService');
            
            return new UserUpdate($user, $em, $persisterService);
        };

        $container[UserQuery::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new UserQuery($em);
        };

        $container[ApplicationStore::class] = function (Container $c) {
            $application = $c->get('Application');
            $persister = $c->get('PersisterService');
            $slugifier = $c->get(Slugify::class);

            return new ApplicationStore($application, $persister, $slugifier);
        };

        $container[ApplicationQuery::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new ApplicationQuery($em);
        };

        $container[ApplicationUpdate::class] = function (Container $c) {
            $persister = $c->get('PersisterService');
            $applicationQuery = $c->get(ApplicationQuery::class);

            return new ApplicationUpdate($persister, $applicationQuery);
        };

        $container[ApplicationDestruction::class] = function (Container $c) {
            $applicationQuery = $c->get(ApplicationQuery::class);
            $persister = $c->get('PersisterService');

            return new ApplicationDestruction($applicationQuery, $persister);
        };
    }
}