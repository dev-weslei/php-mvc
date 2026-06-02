<?php

namespace App\Http\Middleware;

use \Closure;
use \Exception;
use \App\Http\Request;
use \App\Http\Response;

class Queue {

    /**
     * Mapeamento de Middlewares
     *
     * @var array
     */
    private static $map = [];

    /**
     * Middlewares padrão para todas as rotas da aplicação
     *
     * @var array
     */
    private static $middlewaresDefault = [];

    /**
     * Fila de middlewares a serem executados
     * @var array
     */
    private $middlewares = [];

    /**
     * Função de execução do controllador
     *
     * @var Closure
     */
    private $controller;

    /**
     * Argumentos da função do controllador
     *
     * @var array
     */
    private $controllerArgs;

    /**
     * Método responsável por construir a classe de Fila de Middlewares
     *
     * @param array $middlewares
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct($middlewares, $controller, $controllerArgs) {
        //  Leva em consideração a ordem dos middlewares que foram registrados em app.php
        $this->middlewares    = array_merge(self::$middlewaresDefault, $middlewares);
        $this->controller     = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Método responsável por definir o mapeamento de Middlewares
     *
     * @param string $map
     */
    public static function setMap($map) {
        self::$map = $map;
    }

    /**
     * Método responsável por registrar os middlewares que serão utilizados em todas as rotas da aplicação
     *
     * @param string $middlewaresDefault
     */
    public static function setMiddlewaresDefault($middlewaresDefault) {
        self::$middlewaresDefault = $middlewaresDefault;
    }

    /**
     * Método responsável por executar o próximo nível da fila de Middlewares
     *
     * @param Request $request
     * @return Response $response
     */
    public function next($request) {

        // Verifica se a fila está vazia
        if (empty($this->middlewares)) {
            return call_user_func_array($this->controller, $this->controllerArgs);
        }

        // Retorna e remove o Middleware da fila (array)
        $middleware = array_shift($this->middlewares);

        // Verifica se tem o Middleware mapeado
        if (!isset(self::$map[$middleware])) {
            throw new Exception("Problema ao processar o Middleware da Requisição", 500);
        }

        $queue = $this;
        $next = function($request) use($queue) {
            return $queue->next($request);
        };

        // Executa o Middleware
        return (new self::$map[$middleware])->handle($request, $next);
    }
}