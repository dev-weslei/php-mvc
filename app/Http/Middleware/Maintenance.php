<?php

namespace App\Http\Middleware;

use \Closure;
use \App\Http\Request;
use \App\Http\Response;
use \Exception;

class Maintenance {

    /**
     * Método responsável por executar o Middleware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next) {

        // Verifica o estado de manutenção da página
        if (getenv('MAINTENANCE') == 'true') {
            throw new Exception("Página em manutenção, tente novamente mais tarde!", 200);
        }

        // Executa o próximo nível do Middleware
        return $next($request);
    }
}