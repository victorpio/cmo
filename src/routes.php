<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });


    $app->add(function ($req, $res, $next) {
        $response = $next($req, $res);
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });


    // ---> 
    // Import modularized routes
    // ---> 
    $clienteCRUD = require __DIR__ . '/../src/base/Cliente.php'; // Pessoa Entity
    $clienteCRUD($app, $container);
/*

    $peopleCRUD = require __DIR__ . '/../src/base/Veiculo.php'; // Pessoa Entity
    $peopleCRUD($app, $container);
*/
    $veiculoCRUD = require __DIR__ . '/../src/base/Veiculo.php'; // Pessoa Entity
    $veiculoCRUD($app, $container);


    // Catch-all route to serve a 404 Not Found page if none of the routes match
    // NOTE: make sure this route is defined last
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
        $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
        return $handler($req, $res);
    });
};
