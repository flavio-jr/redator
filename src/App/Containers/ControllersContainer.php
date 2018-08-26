<?php

namespace App\Containers;

use Slim\Container;
use App\Controllers\LoginController;
use App\Controllers\UsersController;
use App\Controllers\ApplicationsController;
use App\Controllers\PublicationsController;
use App\Controllers\CategoriesController;
use App\Controllers\UsersController\UserStoreController;
use App\Repositories\UserRepository\Store\UserStore;
use App\Controllers\UsersController\UserUpdateController;
use App\Repositories\UserRepository\Update\UserUpdate;
use App\Controllers\UsersController\UserQueryController;
use App\Repositories\UserRepository\Query\UserQuery;
use App\Controllers\ApplicationsController\UserAppsController;
use App\Repositories\ApplicationRepository\Query\ApplicationQuery;
use App\Controllers\ApplicationsController\AppStoreController;
use App\Repositories\ApplicationRepository\Store\ApplicationStore;
use App\Controllers\ApplicationsController\AppUpdateController;
use App\Repositories\ApplicationRepository\Update\ApplicationUpdate;
use App\Controllers\ApplicationsController\AppDeleteController;
use App\Repositories\ApplicationRepository\Destruction\ApplicationDestruction;
use App\Controllers\CategoriesController\CategoryStoreController;
use App\Repositories\CategoryRepository\Store\CategoryStore;
use App\Controllers\PublicationsController\PublicationStoreController;
use App\Repositories\PublicationRepository\Store\PublicationStore;
use App\Controllers\PublicationsController\PublicationUpdateController;
use App\Repositories\PublicationRepository\Update\PublicationUpdate;
use App\Controllers\PublicationsController\PublicationDestructionController;
use App\Repositories\PublicationRepository\Destruction\PublicationDestruction;
use App\Controllers\PublicationsController\ApplicationPublicationsController;
use App\Repositories\PublicationRepository\Collect\PublicationCollection;
use App\Repositories\UserRepository\Security\UserSecurity;
use App\Controllers\ApplicationMembershipController\MembershipStore;
use App\Repositories\ApplicationTeamRepository\Store\ApplicationTeamStore;
use App\Controllers\ApplicationMembershipController\MembershipDestruction;
use App\Repositories\ApplicationTeamRepository\Destruction\ApplicationMemberDestruction;
use App\Controllers\ApplicationsController\AppOwnershipTransferController;
use App\Repositories\ApplicationRepository\OwnershipUpdate\ApplicationOwnershipTransfer;
use App\Controllers\UsersController\UserDestructionController;
use App\Repositories\UserRepository\Destruction\UserDestruction;
use App\Controllers\ApplicationsController\AppGetController;
use App\Factorys\Application\Query\ApplicationQueryFactory;
use App\Controllers\PublicationsController\PublicationGetController;
use App\Repositories\PublicationRepository\Finder\PublicationSlugFinder;
use App\Controllers\CategoriesController\CategoryUpdateController;
use App\Repositories\CategoryRepository\Update\CategoryUpdate;
use App\Controllers\CategoriesController\CategoryDestructionController;
use App\Repositories\CategoryRepository\Destruction\CategoryDestruction;
use App\Controllers\CategoriesController\CategoriesGetController;
use App\Repositories\CategoryRepository\Collect\CategoryCollection;
use App\Controllers\UsersController\GetUsersController;
use App\Repositories\UserRepository\Collection\UserCollection;
use App\Controllers\UsersController\UserStateController;
use App\Repositories\UserRepository\State\UserStateManager;
use App\Controllers\PublicationsController\PublicationModifierController;
use App\Repositories\PublicationRepository\Modify\PublicationModifier;

class ControllersContainer
{
    public function register(Container $container, array $config)
    {
        $container[LoginController::class] = function ($c) {
            return new LoginController($c->get(UserSecurity::class), $c->get('UserSession'));
        };

        $container[UserStoreController::class] = function (Container $c) {
            return new UserStoreController($c->get(UserStore::class));
        };

        $container[UserUpdateController::class] = function (Container $c) {
            $userUpdateRepository = $c->get(UserUpdate::class);

            return new UserUpdateController($userUpdateRepository);
        };

        $container[UserQueryController::class] = function (Container $c) {
            $userQueryRepository = $c->get(UserQuery::class);

            return new UserQueryController($userQueryRepository);
        };

        $container[UserDestructionController::class] = function (Container $c) {
            $userDestruction = $c->get(UserDestruction::class);

            return new UserDestructionController($userDestruction);
        };

        $container[UserAppsController::class] = function (Container $c) {
            $applicationQuery = $c->get(ApplicationQuery::class);

            return new UserAppsController($applicationQuery);
        };

        $container[UserStateController::class] = function (Container $c) {
            $userStateManager = $c->get(UserStateManager::class);

            return new UserStateController($userStateManager);
        };

        $container[GetUsersController::class] = function (Container $c) {
            $userCollection = $c->get(UserCollection::class);

            return new GetUsersController($userCollection);
        };

        $container[AppGetController::class] = function (Container $c) {
            $applicationQueryFactory = $c->get(ApplicationQueryFactory::class);

            return new AppGetController($applicationQueryFactory);
        };

        $container[AppStoreController::class] = function (Container $c) {
            $appStore = $c->get(ApplicationStore::class);

            return new AppStoreController($appStore);
        };

        $container[AppUpdateController::class] = function (Container $c) {
            $appUpdateRepository = $c->get(ApplicationUpdate::class);

            return new AppUpdateController($appUpdateRepository);
        };

        $container[AppDeleteController::class] = function (Container $c) {
            $appDeleteRepository = $c->get(ApplicationDestruction::class);

            return new AppDeleteController($appDeleteRepository);
        };

        $container[CategoryStoreController::class] = function (Container $c) {
            $categoryStore = $c->get(CategoryStore::class);

            return new CategoryStoreController($categoryStore);
        };

        $container[CategoryUpdateController::class] = function (Container $c) {
            $categoryUpdate = $c->get(CategoryUpdate::class);

            return new CategoryUpdateController($categoryUpdate);
        };

        $container[CategoryDestructionController::class] = function (Container $c) {
            $categoryDestruction = $c->get(CategoryDestruction::class);

            return new CategoryDestructionController($categoryDestruction);
        };

        $container[CategoriesGetController::class] = function (Container $c) {
            $categoryCollection = $c->get(CategoryCollection::class);

            return new CategoriesGetController($categoryCollection);
        };

        $container[PublicationGetController::class] = function (Container $c) {
            $publicationFinder = $c->get(PublicationSlugFinder::class);

            return new PublicationGetController($publicationFinder);
        };

        $container[PublicationStoreController::class] = function (Container $c) {
            $publicationStore = $c->get(PublicationStore::class);

            return new PublicationStoreController($publicationStore);
        };

        $container[PublicationUpdateController::class] = function (Container $c) {
            $publicationUpdate = $c->get(PublicationUpdate::class);

            return new PublicationUpdateController($publicationUpdate);
        };

        $container[PublicationDestructionController::class] = function (Container $c) {
            $publicationDestruction = $c->get(PublicationDestruction::class);

            return new PublicationDestructionController($publicationDestruction);
        };

        $container[PublicationModifierController::class] = function (Container $c) {
            $publicationModifier = $c->get(PublicationModifier::class);

            return new PublicationModifierController($publicationModifier);
        };

        $container[ApplicationPublicationsController::class] = function (Container $c) {
            $publicationCollection = $c->get(PublicationCollection::class);

            return new ApplicationPublicationsController($publicationCollection);
        };

        $container[AppOwnershipTransferController::class] = function (Container $c) {
            $applicationOwnershipTransfer = $c->get(ApplicationOwnershipTransfer::class);

            return new AppOwnershipTransferController($applicationOwnershipTransfer);
        };

        $container[MembershipStore::class] = function (Container $c) {
            $applicationTeamStore = $c->get(ApplicationTeamStore::class);

            return new MembershipStore($applicationTeamStore);
        };

        $container[MembershipDestruction::class] = function (Container $c) {
            $applicationMemberDestruction = $c->get(ApplicationMemberDestruction::class);

            return new MembershipDestruction($applicationMemberDestruction);
        };
    }
}