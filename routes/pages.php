<?php

use App\Http\Response;
use App\Controller\Pages;

// ROTA HOME - RAIZ DO PROJETO
$obRouter->get('/', [
    function() {
        return new Response(200, Pages\Home::getHome());
    }
]);

$obRouter->get('/sobre', [
    function() {
        return new Response(200, Pages\About::getAbout());
    }
]);

$obRouter->get('/depoimentos', [
    function($request) {
        return new Response(200, Pages\Testimonies::getTestimonies($request));
    }
]);

// ROTA DE DEPOIMENTOS - INSERT
$obRouter->post('/depoimentos', [
    function($request) {
        return new Response(200, Pages\Testimonies::insertTestimony($request));
    }
]);

