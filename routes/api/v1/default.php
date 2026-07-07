<?php

use \App\Http\Response;
use App\Controller\Api\Api;

// Rota raiz da API
$obRouter->get('/api/v1', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(200, Api::getDetails($request), 'application/json');
    }
]);
