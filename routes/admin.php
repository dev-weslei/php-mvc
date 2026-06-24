<?php

use App\Http\Response;
use App\Controller\Admin;


// Rota de Login (renderiza a view com o formulário para realizar o login)
$obRouter->get('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request) {
        return new Response(200, Admin\Login::getLogin($request));
    }
]);

// Rota de Login - POST (rota responsável por realizar a verificação de login no back-end)
$obRouter->post('/admin/login', [
    'middlewares' => [
        'required-admin-logout'
    ],
    function($request) {
        return new Response(200, Admin\Login::setLogin($request));
    }
]);

// Rota renderização view ADMIN (usuário logado)
$obRouter->get('/admin', [
    'middlewares' => [
        'required-admin-login'
    ],
    function() {
        return new Response(200, 'ADMIN :)');
    }
]);

// Rota de Login - POST
$obRouter->get('/admin/logout', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Admin\Login::setLogout($request));
    }
]);