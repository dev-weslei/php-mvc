<?php

use App\Http\Response;
use App\Controller\admin\Login;

// Rota responsável por renderizar a view de login
$obRouter->get('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request) {
        return new Response(200, Login::getLogin($request));
    }
]);

// Rota responsável por validar os dados de acesso do usuário
$obRouter->post('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request) {
        return new Response(200, Login::setLogin($request));
    }
]);

// Rota responsável por realizar o logout do usuário
$obRouter->get('/admin/logout', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Login::setLogout($request));
    }
]);