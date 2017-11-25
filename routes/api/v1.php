<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1'
], function ($api) {
    $api->get('/', 'HomeController@index');

    $api->post('login', 'AuthController@login');
    $api->delete('logout', ['uses' => 'AuthController@logout', 'middleware' => 'api.auth']);

    $api->group(['prefix' => 'prop'], function ($api) {
        $api->get('/', 'PropController@index');
        $api->get('{id}', 'PropController@show');
    });

    $api->group(['middleware' => 'api.auth'], function ($api)
    {
        $api->group(['prefix' => 'prop'], function ($api) {
            $api->post('/', 'PropController@store');
            $api->put('{id}', 'PropController@update');
            $api->delete('{id}', 'PropController@destory');

            $api->post('{id}/cover', 'PropController@updateCover');
        });

        $api->group(['prefix' => 'relation'], function ($api) {
            $api->post('/', 'RelationController@store');
            $api->delete('/', 'RelationController@destroy');
        });

        // category
        // $api->get('cat', 'CategoryController@index');
        // $api->post('cat', 'CategoryController@store');
        // $api->delete('cat/{id}', 'CategoryController@destroy');
    });
});