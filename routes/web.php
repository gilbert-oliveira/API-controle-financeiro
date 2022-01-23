<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

/**
 * Grupo de rotas para receitas.
 */
$router->group(['prefix' => 'receitas'], function () use ($router) {
    $router->post('', 'RevenueController@store');

    $router->get('', 'RevenueController@index');
    $router->get('{id}', 'RevenueController@show');

    $router->put('{id}', 'RevenueController@update');

    $router->delete('{id}', 'RevenueController@destroy');
});

/**
 * Grupo de rotas para despesas.
 */
$router->group(['prefix' => 'despesas'], function () use ($router) {
    $router->post('', 'RevenueController@store');

    $router->get('', 'RevenueController@index');
    $router->get('{id}', 'RevenueController@show');

    $router->put('{id}', 'RevenueController@update');

    $router->delete('{id}', 'RevenueController@destroy');
});
