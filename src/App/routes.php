<?php

use App\Middlewares\LoggedUser;
use App\RequestValidators\Login;
use App\RequestValidators\UserRegistration;
use App\RequestValidators\ApplicationRegistration;
use App\RequestValidators\PublicationRegistration;
use App\RequestValidators\CategoryRegistration;
use App\Middlewares\Publications;
use App\RequestValidators\PublicationsInfo;
use App\RequestValidators\MembershipStore;

$app->group('/app', function () {
    $this->post('/login', 'App\Controllers\LoginController:login')->add(new Login());
    $this->post('/users', 'App\Controllers\UsersController\UserStoreController:store')->add(new UserRegistration());

    $this->group('', function () {
        $this->get('', function ($request, $response) {
            return $response->write('You opened the gate');
        });

        $this->group('/users', function () {
            $this->put('', 'App\Controllers\UsersController\UserUpdateController:update')->add(new UserRegistration());
            $this->group('/apps', function () {
                $this->get('', 'App\Controllers\ApplicationsController\UserAppsController:get');
                $this->post('', 'App\Controllers\ApplicationsController\AppStoreController:store')->add(new ApplicationRegistration());
                $this->get('/{app}', 'App\Controllers\ApplicationsController\AppGetController:get');
                $this->put('/{app}', 'App\Controllers\ApplicationsController\AppUpdateController:update')->add(new ApplicationRegistration());
                $this->delete('/{app}', 'App\Controllers\ApplicationsController\AppDeleteController:delete');
                $this->patch('/{app}', 'App\Controllers\ApplicationsController\AppOwnershipTransferController:transferOwnership');

                $this->group('/{app}/publications', function () {
                    $this->get('', 'App\Controllers\PublicationsController\ApplicationPublicationsController:get')->add(new PublicationsInfo());
                    $this->post('', 'App\Controllers\PublicationsController\PublicationStoreController:store')->add(new PublicationRegistration());
                    $this->get('/{publication}', 'App\Controllers\PublicationsController\PublicationGetController:get');
                    $this->put('/{publication}', 'App\Controllers\PublicationsController\PublicationUpdateController:update')->add(new PublicationRegistration());
                    $this->delete('/{publication}', 'App\Controllers\PublicationsController\PublicationDestructionController:destroy');
                });

                $this->group('/{app}/membership', function () {
                    $this->post('', 'App\Controllers\ApplicationMembershipController\MembershipStore:store')->add(new MembershipStore());
                    $this->delete('/{member}', 'App\Controllers\ApplicationMembershipController\MembershipDestruction:destroy');
                });
            });
            $this->get('/{username}', 'App\Controllers\UsersController\UserQueryController:getByUsername');
            $this->delete('/{username}', 'App\Controllers\UsersController\UserDestructionController:destroy');
        });

        $this->group('/categories', function () {
            $this->post('', 'App\Controllers\CategoriesController\CategoryStoreController:store')->add(new CategoryRegistration());
            $this->put('/{category}', 'App\Controllers\CategoriesController\CategoryUpdateController:update')->add(new CategoryRegistration());
            $this->delete('/{category}', 'App\Controllers\CategoriesController\CategoryDestructionController:destroy');
        });
        
    })->add(new LoggedUser($this->getContainer()->get('UserSession'), $this->getContainer()->get('Player')));
});