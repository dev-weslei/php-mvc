<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Http\Request;
use App\Model\Entity\Testimony;
use WilliamCosta\DatabaseManager\Pagination;

class Testimonies extends Template {

    /**
     * Método responsável por obter a renderização dos itens para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getTestimonyItens($request, &$obPagination) {
        $itens = '';

        $quantidadeTotalDepoimentos = Testimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')
                                        ->fetchObject()
                                        ->qtd;

        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $paginaAtual['page'] ?? 1;

        // instância de paginação
        $obPagination = new Pagination($quantidadeTotalDepoimentos, $paginaAtual, 3);
        $results = Testimony::getTestimonies(null, 'id DESC', $obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obTestimony = $results->fetchObject(Testimony::class)) {
            $itens .= View::render('pages/testimonies/item', [
                'nome'          => $obTestimony->nome, 
                'mensagem'      => $obTestimony->mensagem,
                'data_criacao'  => date('d/m/Y H:i:s', strtotime($obTestimony->data_criacao))
            ]);
        }

        // RETORNA OS DEPOIMENTOS
        return $itens;
    }
    
    /**
     * Método responsável por renderizar a view de Depoimentos
     *
     * @param Request $request
     * @return string
     */
    public static function getTestimonies($request) {
        // view da Home
        $content = View::render('pages/testimonies', [
            'itens' => self::getTestimonyItens($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
        ]);

       // view do Template
       return parent::getTemplate('HOME > Weslei', $content);
    }

    /**
     * Método responsável por cadastrar um depoimento
     *
     * @param Request $request
     * @return string
     */
    public static function insertTestimony($request) {
        $postVars = $request->getPostVars(); 
        
        $obTestimony = new Testimony();
        $obTestimony->nome = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        return self::getTestimonies($request);
    }
}   