<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\User as EntityUser;
use Dom\EntityReference;
use WilliamCosta\DatabaseManager\Pagination;
use Exception;

class User extends Api
{
    /**
     * Método responsável por cadastrar novo usuário
     * @param Request $request
     * @return array
     */
    public static function setNewUser($request)
    {
        $postVars = $request->getPostVars();

        if (
            empty($postVars["nome"]) ||
            empty($postVars["email"]) ||
            empty($postVars["senha"])
        ) {
            throw new Exception("Os atributos 'nome', 'email' e 'senha' são obrigatórios para cadastro de usuário!", 400);
        }

        // Verifica se o atributo email é valido
        if (!filter_var($postVars['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("O atributos email " . $postVars['email'] . " não é valido!", 400);
        }

        // Verifica se o email já está sendo utilizdo por outro usuário
        if (EntityUser::getUserByEmail($postVars["email"]) instanceof EntityUser) {
            throw new Exception("O email " . $postVars["email"] . " já está sendo utilizado!", 400);
        }

        $nome  = $postVars["nome"];
        $email = $postVars["email"];
        $senha = $postVars["senha"];

        $obUser = new EntityUser;
        $obUser->nome = $nome;
        $obUser->email = $email;
        $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
        $obUser->cadastrar();

        return [
            'id'    => $obUser->id,
            'nome'  => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por retornar a lista de usuários
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    private static function getUserItens($request, &$obPagination)
    {
        $itens = [];

        $quantidadeTotalUsuarios = EntityUser::getUsers(null, null, null, 'COUNT(*) as qtd')
            ->fetchObject()
            ->qtd;

        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $paginaAtual['page'] ?? 1;

        $obPagination = new Pagination($quantidadeTotalUsuarios, $paginaAtual, 5);
        $results = EntityUser::getUsers(null, 'id ASC', $obPagination->getLimit());

        while ($obUser = $results->fetchObject(EntityUser::class)) {
            $itens[] = [
                'id'     => (int) $obUser->id,
                'nome'   => $obUser->nome,
                'e-mail' => $obUser->email
            ];
        }

        return $itens;
    }

    /**
     * Método responsável por retornar a lista de todos os usuários cadastrados
     * @param Request $request
     * @return void
     */
    public static function getUsers($request)
    {
        return [
            'usuarios' => self::getUserItens($request, $obPagination),
            'paginacao' => parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Método responsável por buscar usuário com base no ID
     * @param integer $id
     * @return array
     */
    public static function getUserById($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("ID '" . $id . "' não é válido.", 400);
        }

        $obUser = EntityUser::getUserById($id);
        if (!$obUser instanceof EntityUser) {
            throw new Exception("Usuário com ID " . $id . " não foi encontrado.", 404);
        }

        return [
            'id'     => $obUser->id,
            'nome'   => $obUser->nome,
            'e-mail' => $obUser->email
        ];
    }

    /**
     * Método responsável por alterar os dados de um usuário com base no seu ID
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setEditUser($request, $id)
    {
        $postVars = $request->getPostVars();

        if (!is_numeric($id)) {
            throw new Exception("ID '" . $id . "' não é válido.", 400);
        }

        $obUser = EntityUser::getUserById($id);
        if (!$obUser instanceof EntityUser) {
            throw new Exception("Usuário com ID " . $id . " não foi encontrado.", 404);
        }

        // Verifica se o e-mail informado já está sendo utilizado.
        $obUserEmail = EntityUser::getUserByEmail($postVars['email']);
        if (
            $obUserEmail instanceof EntityUser &&
            $obUserEmail->email != $obUser->email
        ) {
            throw new Exception("E-mail'" . $postVars['email'] . "' já está sendo utilizado!", 400);
        }

        // Se nova senha, realizar a criptografia
        if (isset($postVars['senha'])) {
            $obUser->senha = password_hash($postVars['senha'], PASSWORD_DEFAULT);
        }

        $obUser->nome  = $postVars['nome']  ?? $obUser->nome;
        $obUser->email = $postVars['email'] ?? $obUser->email;
        $obUser->atualizar();

        return [
            'id'    => $obUser->id,
            'nome'  => $obUser->nome,
            'email' => $obUser->email
        ];
    }

    /**
     * Método responsável por excluir usuário do banco de dados
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setDeleteUser($request, $id)
    {
        if (!is_numeric($id)) {
            throw new Exception("ID '" . $id . "' não é válido.", 400);
        }

        $obUser = EntityUser::getUserById($id);
        if (!$obUser instanceof EntityUser) {
            throw new Exception("Usuário com ID " . $id . " não foi encontrado.", 404);
        }

        if ($obUser->id == $request->getUser()->id) {
            throw new Exception("Não é possível excluir o cadastro do usuário autenticado!", 400);
        }

        $obUser->excluir();

        return [
            'sucesso' => true
        ];
    }
}
