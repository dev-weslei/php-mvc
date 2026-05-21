<?php

require __DIR__ . '/bootstrap/app.php';

use App\Http\Router;

// INICIALIZA O ROTEADOR
$obRouter = new Router(getenv('URL'));

// INCLUI AS ROTAS
include __DIR__.'/routes/pages.php';
 
// IMPRIME O RESPONSE DA ROTA
$obRouter->run()->sendResponse();