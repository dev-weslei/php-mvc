<?php

use \App\Http\Response;
use App\Controller\Api\Testimony;

// Rota de listagem de depoimentos
$obRouter->get('/api/v1/testimonies', [
    'middlewares' => [
        'api'
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
