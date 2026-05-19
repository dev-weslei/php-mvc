<?php

require __DIR__ . '/bootstrap/app.php';

use App\Http\Router;
use App\Utils\View;

View::init([
    'URL' => getenv('URL')
]);

// INICIALIZA O ROTEADOR
$obRouter = new Router(getenv('URL'));

// INCLUI AS ROTAS
include __DIR__.'/routes/pages.php';
 
// IMPRIME O RESPONSE DA ROTA
$obRouter->run()->sendResponse();