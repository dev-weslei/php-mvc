<?php

use App\Controller\Api\User;
use App\Http\Response;

// Rota responsável por listar todos os usuários cadastrados no sistema
$obRouter->get('/api/v1/users', [
    'middlewares' => [
        'api',
        'user-basic-auth',
        'cache'
    ],
    function ($request) {
        return new Response(200, User::getUsers($request), 'application/json');
    }
]);

// Rota de consultas do usuário atual
$obRouter->get('/api/v1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function ($request) {
        return new Response(200, User::getCurrentUser($request), 'application/json');
    }
]);

// Rota responsável por buscar usuário especifico
$obRouter->get('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($id) {
        return new Response(200, User::getUserById($id), 'application/json');
    }
]);

// Rota responsável por atualizar os dados de um usuário
$obRouter->post('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request, $id) {
        return new Response(200, User::setEditUser($request, $id), 'application/json');
    }
]);

// Rota responsável por excluír um usuário
$obRouter->delete('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request, $id) {
        return new Response(200, User::setDeleteUser($request, $id), 'application/json');
    }
]);

// Rota responsável por cadastrar novo usuário
$obRouter->post('/api/v1/users', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request) {
        return new Response(200, User::setNewUser($request), 'application/json');
    }
]);
