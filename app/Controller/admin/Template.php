<?php 

namespace App\Controller\admin;

use App\Utils\View;

class Template {
     /**
     * Metodo responsável por renderizar a estrutura padrão do template de Login Admin
     *
     * @param string $title
     * @param string $body
     */
    public static function getTemplate($title, $body) {
        return View::render('pages/admin/template', [
            'title' => $title,
            'body'  => $body
        ]);
    }
}