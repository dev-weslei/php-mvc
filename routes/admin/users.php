<?php

use App\Http\Response;
use App\Controller\admin\Users;

// Rota responsável por renderizar a view com a lista de todos os usuários cadastrados
$obRouter->get('/admin/users', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, Users::getUsers($request));
    }
]);

// Rota responsável por renderizar a view de criação de um usuário
$obRouter->get('/admin/users/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, Users::getNewUser($request));
    }
]);

// Rota responsável por registrar um novo usuário no banco de dados
$obRouter->post('/admin/users/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request) {
        return new Response(200, Users::setNewUser($request));
    }
]);

// Rota responsável por renderizar o formulário de Editar dados do usuário
$obRouter->get('/admin/users/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request, $id) {
        return new Response(200, Users::getEditUser($request, $id));
    }
]);

// Rota responsável por atualizar os dados do usuário no banco de dados
$obRouter->post('/admin/users/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request, $id) {
        return new Response(200, Users::setEditUser($request, $id));
    }
]);

// Rota responsável por renderizar a view de excluir usuário
$obRouter->get('/admin/users/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request, $id) {
        return new Response(200, Users::getDeleteUser($request, $id));
    }
]);

// Rota responsável por deleta um usuário do banco de dados
$obRouter->post('/admin/users/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function ($request, $id) {
        return new Response(200, Users::setDeleteUser($request, $id));
    }
]);
