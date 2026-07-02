<?php

namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class Testimony {

    /**
     * Indentificador unico do depoimento
     * @var integer
     */
    public $id;

    /**
     * Nome do criador do depoimento
     * @var string
     */
    public $nome;

    /**
     * Mensagem (depoimento) do usuário
     * @var string
     */
    public $mensagem;

    /**
     * Data e hora em que o depoimento foi criado
     * @var string (quando lê do db é uma string)
     */
    public $data_criacao;


    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return boolean
     */
    public function cadastrar() {
        $this->data_criacao = date('Y-m-d H:i:s');
        $this->id = (new Database('testimony'))->insert([
            'nome'         => $this->nome,
            'mensagem'     => $this->mensagem,
            'data_criacao' => $this->data_criacao
        ]);

        return true;
    }

    /**
     * Método responsável por atualizar as informações do depoimento no banco de dados
     * @return boolean
     */
    public function atualizar() {
        return (new Database('testimony'))->update('id ='.$this->id, [
            'nome'         => $this->nome,
            'mensagem'     => $this->mensagem,
            'data_criacao' => $this->data_criacao
        ]);
    }

    /**
     * Método responsável por excluir um depoimento do banco de dados
     * @return boolean
     */
    public function excluir() {
        return (new Database('testimony'))->delete('id ='.$this->id);
    }

    /**
     * Método responsável por retornar os depoimentos
     *
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return \PDOStatement
     */
    public static function getTestimonies(
        $where = null, 
        $order = null, 
        $limit = null,
        $fields = '*'
    ) {
        return (
            new Database('testimony')
        )->select(
            $where, 
            $order, 
            $limit, 
            $fields
        );
    }

    /**
     * Método responsável por retornar um depoimento com base no seu ID
     * @param integer $id
     * @return Testimony
     */
    public static function getTestimonyById($id) {
        return self::getTestimonies('id ='.$id)->fetchObject(self::class);
    }
}