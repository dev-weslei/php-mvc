<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class User
{
    /**
     * Indentificador unico do usuário
     * @var integer
     */
    public $id;

    /**
     * Nome do usuário
     * @var string
     */
    public $nome;

    /**
     * E-mail do usuário
     * @var string
     */
    public $email;

    /**
     * Senha do usuário
     * @var string
     */
    public $senha;

    /**
     * Método responsável por buscar usuário especifico utlizando seu e-mail
     * @param string $email
     * @return User
     */
    public static function getUserByEmail($email)
    {
        return self::getUsers('email = "' . $email . '"')
            ->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar uma instância de User com base no id
     * @param string $id
     * @return User
     */
    public static function getUserById($id)
    {
        return self::getUsers('id = ' . $id)
            ->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar todos os usuários cadastrados
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getUsers(
        $where = null,
        $order = null,
        $limit = null,
        $fields = '*'
    ) {
        return (
            new Database('users')
        )->select(
            $where,
            $order,
            $limit,
            $fields
        );
    }

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     */
    public function cadastrar()
    {
        $this->id = (new Database('users'))->insert([
            'nome'  => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);

        return true;
    }

    /**
     * Método responsável por atualizar o objeto no banco de dados
     * @return boolean
     */
    public function atualizar()
    {
        return (new Database('users'))->update(
            'id = ' . $this->id,
            [
                'nome'  => $this->nome,
                'email' => $this->email,
                'senha' => $this->senha
            ]
        );
    }

    /**
     * Método responsável por excluir o usuário do banco
     * @return boolean
     */
    public function excluir()
    {
        return (new Database('users'))->delete('id = ' . $this->id);
    }
}
