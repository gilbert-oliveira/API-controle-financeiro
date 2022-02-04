<?php

use Laravel\Lumen\Routing\Router;

/** @var Router $router */
$router->get('/', ['as' => 'readme', 'uses' => 'ReadmeController@index']);

$router->group(['middleware' => 'authenticator'], function () use ($router) {
    /**
     * Grupo de rotas para receitas.
     */
    $router->group(['prefix' => 'receitas'], function () use ($router) {

        $router->get('', ['as' => 'revenue.index', 'uses' => 'RevenueController@index']);
        $router->get('{id}', ['as' => 'revenue.show', 'uses' => 'RevenueController@show']);
        $router->get('{year}/{month}', ['as' => 'revenue.show-by-month', 'uses' => 'RevenueController@showByMonth']);

        $router->post('', ['as' => 'revenue.store', 'uses' => 'RevenueController@store']);

        $router->put('{id}', ['as' => 'revenue.update', 'uses' => 'RevenueController@update']);

        $router->delete('{id}', ['as' => 'revenue.destroy', 'uses' => 'RevenueController@destroy']);
    });

    /**
     * Grupo de rotas para despesas.
     */
    $router->group(['prefix' => 'despesas'], function () use ($router) {

        $router->get('', ['as' => 'expense.index', 'uses' => 'ExpenseController@index']);
        $router->get('{id}', ['as' => 'expense.show', 'uses' => 'ExpenseController@show']);
        $router->get('{year}/{month}', ['as' => 'expense.show-by-month', 'uses' => 'ExpenseController@showByMonth']);

        $router->post('', ['as' => 'expense.store', 'uses' => 'ExpenseController@store']);

        $router->put('{id}', ['as' => 'expense.update', 'uses' => 'ExpenseController@update']);

        $router->delete('{id}', ['as' => 'expense.destroy', 'uses' => 'ExpenseController@destroy']);
    });

    $router->get('resumo/{year}/{month}', ['as' => 'resume', 'uses' => 'ResumeController@index']);
});

$router->post('login', ['as' => 'login', 'uses' => 'TokenController@generateToken']);

$router->post('register', ['as' => 'register', 'uses' => 'RegisterController@register']);
