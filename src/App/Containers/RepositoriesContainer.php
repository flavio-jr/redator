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
use App\Repositories\CategoryRepository\Store\CategoryStore;
use App\Repositories\CategoryRepository\Query\CategoryQuery;
use App\Repositories\PublicationRepository\Store\PublicationStore;
use App\Repositories\PublicationRepository\Finder\PublicationSlugFinder;
use App\Repositories\PublicationRepository\Update\PublicationUpdate;
use App\Repositories\PublicationRepository\Destruction\PublicationDestruction;

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
            $slugifier = $c->get(Slugify::class);

            return new ApplicationUpdate($persister, $applicationQuery, $slugifier);
        };

        $container[ApplicationDestruction::class] = function (Container $c) {
            $applicationQuery = $c->get(ApplicationQuery::class);
            $persister = $c->get('PersisterService');

            return new ApplicationDestruction($applicationQuery, $persister);
        };

        $container[CategoryStore::class] = function (Container $c) {
            $category = $c->get('Category');
            $persister = $c->get('PersisterService');

            return new CategoryStore($category, $persister);
        };

        $container[CategoryQuery::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new CategoryQuery($em);
        };

        $container[PublicationStore::class] = function (Container $c) {
            $publication = $c->get('Publication');
            $persister = $c->get('PersisterService');
            $htmlSanitizer = $c->get('HtmlSanitizer');
            $applicationQuery = $c->get(ApplicationQuery::class);
            $categoryQuery = $c->get(CategoryQuery::class);

            return new PublicationStore(
                $publication,
                $persister,
                $htmlSanitizer,
                $applicationQuery,
                $categoryQuery
            );
        };

        $container[PublicationSlugFinder::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();
            $applicationQuery = $c->get(ApplicationQuery::class);

            return new PublicationSlugFinder($em, $applicationQuery);
        };

        $container[PublicationUpdate::class] = function (Container $c) {
            $publicationFinder = $c->get(PublicationSlugFinder::class);
            $persister = $c->get('PersisterService');
            $htmlSanitizer = $c->get('HtmlSanitizer');
            $categoryQuery = $c->get(CategoryQuery::class);

            return new PublicationUpdate(
                $publicationFinder,
                $persister,
                $htmlSanitizer,
                $categoryQuery
            );
        };

        $container[PublicationDestruction::class] = function (Container $c) {
            $publicationFinder = $c->get(PublicationSlugFinder::class);
            $persister = $c->get('PersisterService');

            return new PublicationDestruction(
                $publicationFinder,
                $persister
            );
        };
    }
}