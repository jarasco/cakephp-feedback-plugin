<?php
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Feedback',
    ['path' => '/comments'],
    function ($routes) {
        $routes->connect('/:action/:model/:id', ['controller' => 'Comments'])->setPass(['model', 'id']);
        $routes->connect('/:action/:id', ['controller' => 'Comments'])->setPass(['id']);
        $routes->fallbacks(DashedRoute::class);
    }
);
