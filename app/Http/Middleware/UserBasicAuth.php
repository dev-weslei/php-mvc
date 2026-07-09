<?php

namespace App\Http\Middleware;

use \Closure;
use \App\Http\Request;
use \App\Http\Response;
use Exception;
use App\Model\Entity\User;

class UserBasicAuth
{
    /**
     * Método responsável por retornar a instância do usuário autenticado
     * @return User
     */
    private function getBasicAuthUser()
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        }

        // Busca usuário utilizando o e-mail
        $obUser = User::getUserByEmail($_SERVER['PHP_AUTH_USER']);
        if (!$obUser instanceof User) {
            return false;
        }

        return password_verify($_SERVER['PHP_AUTH_PW'], $obUser->senha) ? $obUser : false;
    }

    /**
     * Método responsável por validar o acesso via HTTP BASIC AUTH
     * @param Request $request
     */
    private function basicAuth($request)
    {
        if ($obUser = $this->getBasicAuthUser()) {
            $request->setUser($obUser);
            return true;
        }

        throw new Exception('Usuário ou senha inválidos!', 403);
    }

    /**
     * Método responsável por executar o Middleware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        // Realiza a validação do acesso via Basic auth
        $this->basicAuth($request);

        // Executa o próximo nível do Middleware
        return $next($request);
    }
}
