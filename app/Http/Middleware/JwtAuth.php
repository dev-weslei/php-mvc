<?php

namespace App\Http\Middleware;

use \Closure;
use \App\Http\Request;
use \App\Http\Response;
use Exception;
use App\Model\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtAuth
{
    /**
     * Método responsável por retornar a instância do usuário autenticado
     * @param Request $request
     * @return User
     */
    private function getJwtAuthUser($request)
    {
        $headers = $request->getHeaders();
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';
        
        try {
            $decode = (array) JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));//code...
        } catch (\Throwable $th) {
            throw new Exception("Token inválido!", 403);
        }

        // Busca usuário utilizando o e-mail
        $email = $decode['email'] ?? '';
        $obUser = User::getUserByEmail($email);
     
        return $obUser instanceof User ? $obUser : false;
    }

    /**
     * Método responsável por validar o acesso via JWT 
     * @param Request $request
     */
    private function auth($request)
    {
        if ($obUser = $this->getJwtAuthUser($request)) {
            $request->setUser($obUser);
            return true;
        }

        throw new Exception('Token inválido.', 403);
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
        // Realiza a validação do acesso via JWT
        $this->auth($request);

        // Executa o próximo nível do Middleware
        return $next($request);
    }
}
