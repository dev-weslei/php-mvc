<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Model\Entity\Organization;

class Home extends Template {

    public static function getHome() {
        $obOrganization = new Organization();

        // view da Home
        $content = View::render('pages/home', [
            'name'        => $obOrganization->name,
       ]);

       // view do Template
       return parent::getTemplate('HOME > Weslei', $content);
    }
}