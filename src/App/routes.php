<?php

$app->get('/', function ($request, $response) {
    $em = $this->get('em');
    return $response->write('Hello, world');
});