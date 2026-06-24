<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class User {
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
    public static function getUserByEmail($email) {
        return (
            new Database('users')
            )
        ->select('email = "'.$email.'"')
        ->fetchObject(self::class);
    }
}