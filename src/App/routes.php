<?php

use App\Middlewares\LoggedUser;
use App\RequestValidators\Login;
use App\RequestValidators\UserRegistration;
use App\RequestValidators\ApplicationRegistration;
use App\RequestValidators\PublicationRegistration;
use App\RequestValidators\CategoryRegistration;
use App\Middlewares\Publications;
use App\RequestValidators\PublicationsInfo;

$app->group('/app', function () {
    $this->post('/login', 'App\Controllers\LoginController:login')->add(new Login());

    $this->group('', function () {
        $this->get('', function ($request, $response) {
            return $response->write('You opened the gate');
        });

        $this->group('/users', function () {
            $this->post('', 'App\Controllers\UsersController:store')->add(new UserRegistration());
            $this->put('/{user_id}', 'App\Controllers\UsersController:update')->add(new UserRegistration());
            $this->get('/username-availaibility/{username}', 'App\Controllers\UsersController:usernameAvailaibility');
            $this->get('/apps', 'App\Controllers\ApplicationsController:userApps');
        });

        $this->group('/applications', function () {
            $this->post('', 'App\Controllers\ApplicationsController:store')->add(new ApplicationRegistration());
            $this->put('/{app_id}', 'App\Controllers\ApplicationsController:update')->add(new ApplicationRegistration());
            $this->delete('/{app_id}', 'App\Controllers\ApplicationsController:destroy');
        });

        $this->group('/publications', function () {
            $this->get('/{application_id}', 'App\Controllers\PublicationsController:getPublications')
                ->add(new PublicationsInfo())
                ->add(new Publications($this->getContainer()->get('ApplicationRepository')));

            $this->post('', 'App\Controllers\PublicationsController:store')->add(new PublicationRegistration());
            $this->put('/{publication_id}', 'App\Controllers\PublicationsController:update')->add(new PublicationRegistration());
            $this->delete('/{publication_id}', 'App\Controllers\PublicationsController:destroy');
        });

        $this->group('/categories', function () {
            $this->post('', 'App\Controllers\CategoriesController:store')->add(new CategoryRegistration());
        });
        
    })->add(new LoggedUser($this->getContainer()->get('UserSession'), $this->getContainer()->get('Player')));
});