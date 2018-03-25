<?php

use App\Middlewares\LoggedUser;

$app->group('/app', function () {
    $this->post('/login', 'App\Controllers\LoginController:login');

    $this->group('', function () {
        $this->get('', function ($request, $response) {
            return $response->write('You opened the gate');
        });
        
    })->add(new LoggedUser($this->getContainer()->get('UserSession')));
});