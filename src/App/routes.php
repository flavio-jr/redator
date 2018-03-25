<?php

$app->group('/app', function () {
    $this->get('', function ($request, $response) {
        return $response->write('Hello, world');
    });
    
    $this->post('/login', 'App\Controllers\LoginController:login');
});