<?php

namespace App\Http\Middleware;

use \Closure;
use \App\Http\Request;
use \App\Http\Response;
use App\Session\Admin\Login as SessionAdminLogin;

class RequiredAdminLogout {

     /**
     * Método responsável por executar o Middleware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next) {
        // Verifica se o usuário está logado
        if (SessionAdminLogin::isLogged()) {
            $request->getRouter()->redirect('/admin');
        }

        return $next($request);
    }
}