<?php

use \App\Http\Response;
use App\Controller\Api\Testimony;

// Rota de listagem de depoimentos
$obRouter->get('/api/v1/testimonies', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function ($request) {
        return new Response(200, Testimony::getTestimonies($request), 'application/json');
    }
]);

// Rota de consulta individual de depoimentos
$obRouter->get('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api'
    ],
    function ($id) {
        return new Response(200, Testimony::getTestimony($id), 'application/json');
    }
]);

// Rota de cadastro de depoimentos
$obRouter->post('/api/v1/testimonies', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request) {
        // Retorno 201: Created
        return new Response(201, Testimony::setNewTestimony($request), 'application/json');
    }
]);

// Rota responsável por editar um depoimento
$obRouter->put('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request, $id) {
        return new Response(200, Testimony::setEditTestimony($request, $id), 'application/json');
    }
]);

// Rota responsável por excluír um depoimento
$obRouter->delete('/api/v1/testimonies/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request, $id) {
        return new Response(200, Testimony::setDeleteTestimony($request, $id), 'application/json');
    }
]);
