<?php

use App\Middlewares\LoggedUser;
use App\RequestValidators\Login;

$app->group('/app', function () {
    $this->post('/login', 'App\Controllers\LoginController:login')->add(new Login());

    $this->group('', function () {
        $this->get('', function ($request, $response) {
            return $response->write('You opened the gate');
        });
        
    })->add(new LoggedUser($this->getContainer()->get('UserSession'), $this->getContainer()->get('Player')));
});