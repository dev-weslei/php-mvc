<?php

use App\Http\Response;
use App\Controller\admin\Testimonies;

// Rota responsável por renderizar a view com todos os depoimentos cadastrados
$obRouter->get('/admin/testimonies', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Testimonies::getTestimonies($request));
    }
]);

// Rota responsável por renderizar a view de cadastro de depoimentos
$obRouter->get('/admin/testimonies/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function() {
        return new Response(200, Testimonies::getNewTestimony());
    }
]);

// Rota responsável por persistir o novo depoimento do banco de dados
$obRouter->post('/admin/testimonies/new', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Testimonies::setNewTestimony($request));
    }
]);

// Rota responsável por renderizar o formulário de edição de depoimento
$obRouter->get('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Testimonies::getEditTestimony($request, $id));
    }
]);

// Rota responsável alterar as informações de um depoimento especifico
$obRouter->post('/admin/testimonies/{id}/edit', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Testimonies::setEditTestimony($request, $id));
    }
]);

// Rota responsável por renderizar a view de exclusão de um depoimento
$obRouter->get('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Testimonies::getDeleteTestimony($request, $id));
    }
]);

// Rota responsável por realizar a exclusão de um Depoimento
$obRouter->post('/admin/testimonies/{id}/delete', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request, $id) {
        return new Response(200, Testimonies::setDeleteTestimony($request, $id));
    }
]);