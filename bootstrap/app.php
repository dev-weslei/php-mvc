<?php

// COMPOSER - AUTOLOAD
require __DIR__ . '/../vendor/autoload.php';

use App\Common\Enviroment;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

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

// Define o mapeamento (registrar) de Middlewares
MiddlewareQueue::setMap([
    'maintenance'           => App\Http\Middleware\Maintenance::class,
    'required-admin-logout' => App\Http\Middleware\RequiredAdminLogout::class,
    'required-admin-login'  => App\Http\Middleware\RequiredAdminLogin::class
]);

// Mapeamento de Middlewares que serão executados em todas as rotas da aplicação
MiddlewareQueue::setMiddlewaresDefault([
    'maintenance'
]);
