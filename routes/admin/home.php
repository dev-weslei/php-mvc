<?php 

use App\Http\Response;
use App\Controller\admin\Home;

// Rota responsável por renderizar a view de Home do painel de administração
$obRouter->get('/admin', [
    'middlewares' => [
        'required-admin-login'
    ],
    function() {
        return new Response(200, Home::getHome());
    }
]);
