<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Http\Request;
use App\Model\Entity\Testimony;

class Testimonies extends Template {

    /**
     * Método responsável por obter a renderização dos itens para a página
     *
     * @return string
     */
    private static function getTestimonyItens() {
        $itens = '';

        // RESULTADOS DA PÁGINA
        // $results recebe a query que foi gerada para capturar todos os depoimentos
        $results = Testimony::getTestimonies(null, 'id DESC');

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

    public static function getTestimonies() {
        // view da Home
        $content = View::render('pages/testimonies', [
            'itens' => self::getTestimonyItens(),
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

        return self::getTestimonies();
    }
}   