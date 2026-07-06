<?php

namespace App\Controller\admin;

use App\Controller\admin\Template;
use App\Http\Request;
use App\Utils\View;
use App\Model\Entity\User;
use WilliamCosta\DatabaseManager\Pagination;

class Users extends Template
{

    /**
     * Método responsável por retornar a mensagem de status
     *
     * @param Request $request
     * @return Alert
     */
    private static function getStatus($request)
    {
        $queryParams = $request->getQueryParams();
        if (!isset($queryParams['status'])) {
            return '';
        }

        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Usuário criado com sucesso!');
            case 'updated':
                return Alert::getSuccess('Usuário editado com sucesso!');
            case 'deleted':
                return Alert::getSuccess('Usuário excluído com sucesso!');
            case 'duplicated':
                return Alert::getError('E-mail já está sendo utilizado por outro usuário!');
        }
    }

    /**
     * Método responsável por retornar a view com a lista de usuários cadastrados
     * @param Request $request
     * @return string
     */
    public static function getUsers($request)
    {

        $content = View::render('admin\modules\users\index', [
            'itens'      => self::getUserItens($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => self::getStatus($request)
        ]);

        return parent::getPanel(
            'Usuários > WDEV',
            $content,
            'users'
        );
    }

    /**
     * Método responsável por renderizar o link excluír usuário
     * @param integer $idUsuario
     * @return string
     */
    private static function getElementExcluir($idUsuario)
    {
        return view::render('admin/modules/users/excluir', [
            'URL' => URL,
            'id' => $idUsuario
        ]);
    }

    /**
     * Método responsável por renderizar os itens da view da listagem de usuários
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getUserItens($request, &$obPagination)
    {
        $itens = '';
        $quantidadeTotalUsuarios = User::getUsers(null, null, null, 'COUNT(*) AS qtd')
            ->fetchObject()
            ->qtd;
        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $paginaAtual['page'] ?? 1;

        $obPagination = new Pagination($quantidadeTotalUsuarios, $paginaAtual, 4);
        $results = User::getUsers(null, 'id ASC', $obPagination->getLimit());

        while ($obUser = $results->fetchObject(User::class)) {
            $itens .= View::render('admin/modules/users/item', [
                'id'    => $obUser->id,
                'nome'  => $obUser->nome,
                'email' => $obUser->email,
                'excluir' => $obUser->id != $_SESSION['admin']['usuario']['id'] ? self::getElementExcluir($obUser->id) : ''
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por renderizar a view com o formulário de criação de um usuário
     * @param Request $request
     * @return string
     */
    public static function getNewUser($request)
    {
        $content = View::render('admin/modules/users/form', [
            'title'    => 'Criar usuário',
            'nome'     => '',
            'email'    => '',
            'status'   => self::getStatus($request),
            'required' => 'required'
        ]);

        return parent::getPanel('Novo usuário > WDEV', $content, 'users');
    }

    /**
     * Método responsável por registrar um novo usuário no banco de dados
     * @param Request $request
     * @return void
     */
    public static function setNewUser($request)
    {
        $postVars = $request->getPostVars();
        $nome  = $postVars['nome'];
        $email = $postVars['email'];
        $senha = $postVars['senha'];

        $obUser = User::getUserByEmail($email);
        if ($obUser instanceof User) {
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        $obUser = new User();
        $obUser->nome  = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser->cadastrar();

        $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=created');
    }

    /**
     * Método responsável por renderizdar a view de Editar dados de um usuário
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditUser($request, $id)
    {
        $obUser = User::getUserById($id);
        if (!$obUser instanceof User) {
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('/admin/modules/users/form', [
            'title'    => 'Editar usuário',
            'nome'     => $obUser->nome,
            'email'    => $obUser->email,
            'status'   => self::getStatus($request),
            'required' => '',
        ]);

        return parent::getPanel('Editar usuário > WDEV', $content, 'users');
    }

    /**
     * Método responsável por atualizar os dados do usuário no banco de dados
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditUser($request, $id)
    {
        $obUser = User::getUserById($id);
        if (!$obUser instanceof User) {
            $request->getRouter()->redirect('admin/users');
        }

        $postVars = $request->getPostVars();
        $nome  = $postVars['nome'] ?? '';
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'];
        if (!empty($senha)) {
            $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        }

        $obUserEmail = User::getUserByEmail($email);
        if ($obUserEmail instanceof User && $id != $obUserEmail->id) {
            $request->getRouter()->redirect('/admin/users/' . $id . '/edit?status=duplicated');
        }

        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->atualizar();

        $request->getRouter()->redirect('/admin/users/' . $obUser->id . '/edit?status=updated');
    }

    /**
     * Método responsável por renderizar a view de exclusão de usuário
     *
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getDeleteUser($request, $id)
    {
        $obUser = User::getUserById($id);
        if (!$obUser instanceof User) {
            $request->getRouter()->redirect('/admin/users');
        }

        $content = View::render('admin/modules/users/delete', [
            'nome'  => $obUser->nome,
            'email' => $obUser->email
        ]);

        return parent::getPanel(
            'Excluir usuário > WDEV',
            $content,
            'testimonies'
        );
    }

    /**
     * Método responsável por excluir o usuário do banco de dados
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public static function setDeleteUser($request, $id)
    {
        $obUser = User::getUserById($id);
        if (!$obUser instanceof User) {
            $request->getRouter()->redirect('/admin/users');
        }

        $obUser->excluir();

        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
}
