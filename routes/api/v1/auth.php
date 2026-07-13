<?php

use \App\Http\Response;
use App\COntroller\Api\Auth;

// Rota raiz da API
$obRouter->post('/api/v1/auth', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(200, Auth::generateToken($request), 'application/json');
    }
]);
