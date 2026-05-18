<?php

// sudo tail -f /var/log/apache2/error.log

require __DIR__ . '/vendor/autoload.php';

use App\Http\Router;
use App\Utils\View;

define('URL', 'http://localhost/php-mvc');

View::init([
    'URL' => URL
]);

// INICIALIZA O ROTEADOR
$obRouter = new Router(URL);

// INCLUI AS ROTAS
include __DIR__.'/routes/pages.php';
 
// IMPRIME O RESPONSE DA ROTA
$obRouter->run()->sendResponse();