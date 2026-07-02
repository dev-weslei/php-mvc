<?php

namespace App\Controller\admin;

use App\Utils\View;
use App\Http\Request;

class Home extends Template {

    /**
     * Método responsável por renderizar a view de Home do painel de adminstração
     * @return string
     */
    public static function getHome() {
        $content = View::render('admin/modules/home/index', [
            
        ]);

        return parent::getPanel('Home > WDEV', $content, 'home');
    }
}