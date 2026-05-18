<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Organization;

class About extends Template {


    /**
     * Método responsável por retornar o conteúdo (view) da página de Sobre
     *
     * @return string
     */
    public static function getAbout() {
        $obOrganization = new Organization();

        // view da Home
        $content = View::render('pages/about', [
            'name'        => $obOrganization->name,
            'description' => $obOrganization->description,
            'site'        => $obOrganization->website
       ]);

       // view do Template
       return parent::getTemplate('SOBRE > Weslei', $content);
    }
}