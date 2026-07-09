<?php

namespace App\Controller\Api;

use App\Http\Request;
use App\Model\Entity\Testimony as TestimonyEntity;
use Exception;
use WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Api
{
    /**
     * Método responsável por obter a renderização dos itens para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return array
     */
    private static function getTestimonyItens($request, &$obPagination)
    {
        $itens = [];

        $quantidadeTotalDepoimentos = TestimonyEntity::getTestimonies(null, null, null, 'COUNT(*) as qtd')
            ->fetchObject()
            ->qtd;

        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $paginaAtual['page'] ?? 1;

        // instância de paginação
        $obPagination = new Pagination($quantidadeTotalDepoimentos, $paginaAtual, 5);
        $results = TestimonyEntity::getTestimonies(null, 'id ASC', $obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obTestimony = $results->fetchObject(Testimony::class)) {
            $itens[] = [
                'id'    => (int) $obTestimony->id,
                'nome'  => $obTestimony->nome,
                'texto' => $obTestimony->mensagem,
                'data'  => $obTestimony->data_criacao
            ];
        }

        // Retorna a lista de depoimentos
        return $itens;
    }

    /**
     * Método responsável por retornar os depoimentos cadastrados
     * @param Request $request
     * @return array
     */
    public static function getTestimonies($request)
    {
        return [
            'depoimentos' => self::getTestimonyItens($request, $obPagination),
            'paginacao'   => parent::getPagination($request, $obPagination)
        ];
    }

    /**
     * Método responsável por retornar os detalhes de um depoimento
     * @param integer $id
     * @return array
     */
    public static function getTestimony($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("O ID '" . $id . "' não é válido.", 400);
        }

        $obTestimony = TestimonyEntity::getTestimonyById($id);
        if (!$obTestimony instanceof TestimonyEntity) {
            throw new Exception("O depoimento com ID " . $id . " não foi encontrado.", 404);
        }

        return [
            'id'            => (int) $obTestimony->id,
            'nome'          => $obTestimony->nome,
            'texto'         => $obTestimony->mensagem,
            'data-criacao'  => $obTestimony->data_criacao
        ];
    }

    /**
     * Método responsável por cadastrar um novo depoimento
     * @param Request $request
     * @return void
     */
    public static function setNewTestimony($request)
    {
        $postVars = $request->getPostVars();
        if (!isset($postVars['nome']) || !isset($postVars['mensagem'])) {
            throw new Exception("Os campos 'nome' e 'mensagem' são obrigatórios!", 400);
        }

        $obTestimony           = new TestimonyEntity;
        $obTestimony->nome     = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        // Retorna os detalhes do depoimento cadastrado
        return [
            'id'            => (int) $obTestimony->id,
            'nome'          => $obTestimony->nome,
            'texto'         => $obTestimony->mensagem,
            'data-criacao'  => $obTestimony->data_criacao
        ];
    }

    /**
     * Método responsável por atualizar um depoimento
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function setEditTestimony($request, $id)
    {
        $postVars = $request->getPostVars();
        if (!isset($postVars['nome']) || !isset($postVars['mensagem'])) {
            throw new Exception("Os campos 'nome' e 'mensagem' são obrigatórios!", 400);
        }

        $obTestimony = TestimonyEntity::getTestimonyById($id);
        if (!$obTestimony instanceof TestimonyEntity) {
            throw new Exception("O depoimento com ID " . $id . " não foi encontrado.", 404);
        }

        $obTestimony->nome     = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->atualizar();

        // Retorna os detalhes do depoimento atualizado
        return [
            'id'           => (int) $obTestimony->id,
            'nome'         => $obTestimony->nome,
            'texto'        => $obTestimony->mensagem,
            'data-criacao' => $obTestimony->data_criacao
        ];
    }

    /**
     * Método responsável por excluir um Depoimento do banco de dados
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public static function setDeleteTestimony($request, $id)
    {
        $obTestimony = TestimonyEntity::getTestimonyById($id);
        if (!$obTestimony instanceof TestimonyEntity) {
            throw new Exception("O depoimento com ID " . $id . " não foi encontrado.", 404);
        }

        $obTestimony->excluir();

        return [
            'sucesso' => 'Depoimento com ID ' . $id . ' excluído com sucesso!'
        ];
    }
}
