<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\User as EntityUser;
use Firebase\JWT\JWT;
use Exception;

class Auth extends Api {

    /**
     * Método responsável por gerar o token JWT (JSON Web Token)
     *
     * @param Request $request
     * @return array
     */
    public static function generateToken($request) {
        $postVars = $request->getPostVars();
        if (!isset($postVars['email']) || !isset($postVars['senha'])) {
            throw new Exception("Os campos 'email' e 'senha' são obrigatórios!", 400);
        }

        $obUser = EntityUser::getUserByEmail($postVars['email']);
        if (
            !$obUser instanceof EntityUser || 
            !password_verify($postVars['senha'], $obUser->senha)
        ) {
            throw new Exception("Usuário não encontrado. Favor verifique o e-mail ou senha estão corretos.", 400);
        }

        $payload = [
            'email' => $obUser->email,
        ];

        // Retorna o token JWT gerado
        return [
            'token' => JWT::encode($payload, getenv('JWT_KEY'), 'HS256')     
        ];
    }
}