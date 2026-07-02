<?php

namespace App\Controller\admin;

use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Template {

    /**
     * Método responsável por renderizar a view de Login
     *
     * @param Request $request
     * @param string $errorMessage
     * @return void
     */
    public static function getLogin($request, $errorMessage = null) {
        // renderizacao view status
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        $body = View::render('/admin/login', [
            'status' => $status
        ]);

        // Retorna a view completa
        return parent::getTemplate('Login > WDEV', $body);
    }

    /**
     * Método responsável por definir o login do usuário
     *
     * @param Request $request
     * @return boolean
     */
    public static function setLogin($request) {
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        // busa o usuário pelo email
        $obUser = User::getUserByEmail($email);

        if (!$obUser instanceof User || !password_verify($senha, $obUser->senha)) {
            return self::getLogin($request, 'Email ou senha inválidos!');
        }

        // Cria a sessão de login do usuário Admin
        SessionAdminLogin::login($obUser);

        // Redireciona o usuário para a Home do ADMIN
        $request->getRouter()->redirect('/admin');
    } 
    
    /**
     * Undocumented function
     * @param Request $request
     */
    public static function setLogout($request) {
        // Destroi a sessão de login do usuário Admin
        SessionAdminLogin::logout();

        // Redireciona o usuário para a view de Login
        $request->getRouter()->redirect('/admin/login');
    }
}