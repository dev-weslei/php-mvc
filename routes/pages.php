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

// ROTA DINÂMICA
$obRouter->get('/pagina/{idPagina}/{acao}', [
    function($idPagina, $acao) {
        return new Response(200, "Página ". $idPagina . " - " . $acao);
    }
]);
