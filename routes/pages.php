<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA HOME - RAIZ DO PROJETO
$obRouter->get('/', [
    'middlewares' => [
        'required-admin-login'
    ],
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);

// ROTA SOBRE - GET
$obRouter->get('/sobre', [
    function() {
        return new Response(200, Pages\About::getAbout());
    }
]);

// ROTA DE DEPOIMENTOS - GET
$obRouter->get('/depoimentos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Pages\Testimonies::getTestimonies($request));
    }
]);

// ROTA DE DEPOIMENTOS - INSERT
$obRouter->post('/depoimentos', [
    'middlewares' => [
        'required-admin-login'
    ],
    function($request) {
        return new Response(200, Pages\Testimonies::insertTestimony($request));
    }
]);

