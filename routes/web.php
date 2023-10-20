<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
//

//$router->group(
    //['middleware' => ['whitelistips']],
    //function() use ($router) {
        $router->get('/', function () use ($router) {
           return response('', 501);
        });

        $router->post('/auth/login', 'AuthController@authenticate');
        $router->get('/health', 'ApiController@getHealth');
        $router->group(
            ['middleware' => ['jwt.auth', 'admin']],
            function () use ($router) {

                $router->post('/auth/register', 'AuthController@register');
                $router->post('/auth/create/token', 'AuthController@createToken');
                $router->post('/auth/update', 'AuthController@update');

                $router->get('/auth/check-admin', function () {
                    return response()->json('You are logged-in with as admin.');
                });

            }
        );

        $router->group(
            ['middleware' => ['jwt.auth','log'], 'prefix' => 'service'],
            function () use ($router) {
                $router->get('users', function () {
                    $users = \App\Models\User::all();
                    return response()->json($users);
                });
                $router->get('list', 'ServiceController@listService');
                $router->get('get/{sid}', 'ServiceController@getService');
				$router->get('template/{sid}', 'ServiceController@getTemplates');
                $router->post('provisioning/{sid}', 'ServiceController@Provisioning');
                $router->get('provisioning/status/{sid}', 'ServiceController@provisioningStatus');
                $router->get('console/{sid}', 'ServiceController@getConsole');
                $router->get('status/{sid}', 'ServiceController@getStatus');
            }
        );
        $router->group(
            ['middleware' => 'jwt.auth', 'prefix' => 'ddos'],
            function () use ($router) {
                $router->get('users', function () {
                    $users = \App\Models\User::all();
                    return response()->json($users);
                });
                $router->get('status/{ip}', 'DdosController@getStatus');
                $router->put('routing/{ip}', 'DdosController@setRouting');
                $router->get('incidents/{ip}', 'DdosController@getIncidents');
                $router->post('thresholds/{ip}', 'DdosController@setThresholds');
                $router->get('thresholds', 'DdosController@getThresholds');
                $router->delete('thresholds/{uid}', 'DdosController@removeThresholds');
            }
        );
$router->group(
    ['middleware' => ['jwt.auth','log'], 'prefix' => 'dns'],
    function () use ($router) {
        $router->get('users', function () {
            $users = \App\Models\User::all();
            return response()->json($users);
        });
        $router->put('addPtr/{ip}', 'DnsController@setPtr');

    }
);

    //});

