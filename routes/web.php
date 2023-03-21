<?php

$routes = [
    '/' => 'HomeController@home',
    '/hello' => 'HomeController@hello'
];

Router::addRoute($routes);
Router::run();
