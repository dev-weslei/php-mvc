<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Testimony {

    /**
     * Indentificador unico do depoimento
     *
     * @var integer
     */
    public $id;

    /**
     * Nome do criador do depoimento
     *
     * @var string
     */
    public $nome;

    /**
     * Mensagem (depoimento) do usuário
     *
     * @var string
     */
    public $mensagem;

    /**
     * Data e hora em que o depoimento foi criado
     *
     * @var string (quando lê do db é uma string)
     */
    public $data_criacao;


    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     *
     * @return boolean
     */
    public function cadastrar() {

        // DEFINE A DATA
        $this->data_criacao = date('Y-m-d H:i:s');

        // INSERE O DEPOIMENTO NO BANCO DE DADOS
        $this->id = (new Database('depoimentos'))->insert([
            'nome'        => $this->nome,
            'mensagem'    => $this->mensagem,
            'data_criacao' => $this->data_criacao
        ]);
 
        echo '<pre>';
        print_r($this);
        echo '</pre>';
    }
}