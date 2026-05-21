<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Http\Request;
use App\Model\Entity\Testimony;

class Testimonies extends Template {

    public static function getTestimonies() {
        // view da Home
        $content = View::render('pages/testimonies', []);

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