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
use App\Repositories\CategoryRepository\Store\CategoryStore;
use App\Repositories\CategoryRepository\Query\CategoryQuery;
use App\Repositories\PublicationRepository\Store\PublicationStore;
use App\Repositories\PublicationRepository\Finder\PublicationSlugFinder;
use App\Repositories\PublicationRepository\Update\PublicationUpdate;
use App\Repositories\PublicationRepository\Destruction\PublicationDestruction;
use App\Repositories\PublicationRepository\Collect\PublicationCollection;
use App\Querys\Publications\PublicationQuery;
use App\Repositories\UserRepository\Security\UserSecurity;
use App\Repositories\UserRepository\Finder\UserFinder;
use App\Repositories\UserMasterRepository\Query\UserMasterQuery;
use App\Repositories\UserMasterRepository\Store\UserMasterStore;
use App\Repositories\UserMasterRepository\Update\UserMasterUpdate;
use App\Repositories\ApplicationRepository\Query\ApplicationMasterQuery;
use App\Factorys\Application\Query\ApplicationQueryFactory;
use App\Repositories\ApplicationTeamRepository\Store\ApplicationTeamStore;
use App\Repositories\ApplicationRepository\Query\ApplicationTeamQuery;
use App\Repositories\ApplicationRepository\Finder\ApplicationSlugFinder;

class RepositoriesContainer
{
    public function register(Container $container, array $config)
    {
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

        $container[UserSecurity::class] = function (Container $c) {
            $userSession = $c->get('UserSession');
            $userQuery = $c->get(UserQuery::class);

            return new UserSecurity($userSession, $userQuery);
        };

        $container[UserFinder::class] = function (Container $c) {
            $em = $c->get('orm')->getEntityManager();

            return new UserFinder($em);
        };

        $container[UserMasterQuery::class] = function (Container $c) {
            $em = $c->get('orm')->getEntityManager();

            return new UserMasterQuery($em);
        };

        $container[UserMasterStore::class] = function (Container $c) {
            $user = $c->get('User');
            $persister = $c->get('PersisterService');
            $userMasterQuery = $c->get(UserMasterQuery::class);

            return new UserMasterStore($user, $persister, $userMasterQuery);
        };

        $container[UserMasterUpdate::class] = function (Container $c) {
            $userMasterQuery = $c->get(UserMasterQuery::class);
            $persister = $c->get('PersisterService');

            return new UserMasterUpdate($userMasterQuery, $persister);
        };

        $container[ApplicationStore::class] = function (Container $c) {
            $application = $c->get('Application');
            $persister = $c->get('PersisterService');

            return new ApplicationStore($application, $persister);
        };

        $container[ApplicationQuery::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new ApplicationQuery($em);
        };

        $container[ApplicationMasterQuery::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new ApplicationMasterQuery($em);
        };

        $container[ApplicationTeamQuery::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new ApplicationTeamQuery($em);
        };

        $container[ApplicationUpdate::class] = function (Container $c) {
            $persister = $c->get('PersisterService');
            $applicationQueryFactory = $c->get(ApplicationQueryFactory::class);

            return new ApplicationUpdate($persister, $applicationQueryFactory);
        };

        $container[ApplicationDestruction::class] = function (Container $c) {
            $applicationQueryFactory = $c->get(ApplicationQueryFactory::class);
            $persister = $c->get('PersisterService');

            return new ApplicationDestruction($applicationQueryFactory, $persister);
        };

        $container[ApplicationSlugFinder::class] = function (Container $c) {
            $em = $c->get('doctrine')->getEntityManager();

            return new ApplicationSlugFinder($em);
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

        $container[PublicationCollection::class] = function (Container $c) {
            $applicationQuery = $c->get(ApplicationQuery::class);
            $publicationQuery = $c->get(PublicationQuery::class);

            return new PublicationCollection($applicationQuery, $publicationQuery);
        };

        $container[ApplicationTeamStore::class] = function (Container $c) {
            $userQuery = $c->get(UserQuery::class);
            $applicationQuery = $c->get(ApplicationQuery::class);
            $persister = $c->get('PersisterService');

            return new ApplicationTeamStore($userQuery, $applicationQuery, $persister);
        };
    }
}