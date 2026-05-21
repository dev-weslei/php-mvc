<?php

// COMPOSER - AUTOLOAD
require __DIR__ . '/../vendor/autoload.php';

use App\Common\Enviroment;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;

// CARREGA AS VARIAVEIS DE AMBIENTE DO PROJETO
Enviroment::load(__DIR__.'/../');

// DEFINE AS CONFIGURAÇÕES DO BANCO DE DADOS
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT'),
);

View::init([
    'URL' => getenv('URL')
]);


