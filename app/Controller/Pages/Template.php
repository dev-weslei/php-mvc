<?php

namespace App\Controller\Pages;
use App\Utils\View;
use App\Pages\Home;

class Template {

    /**
     * Método responsável por retornar o conteúdo do header
     *
     * @return string
     */
    private static function getHeader() {
        return View::render('pages/header');
    }

    /**
     * Método responsável por retornar o conteúdo do footer
     *
     * @return string
     */
    private static function getFooter() {
        return View::render('pages/footer');
    }

    public static function getTemplate($title, $body) {
        return View::render('pages/template', [
            'title'  => $title,
            'header' => self::getHeader(),
            'body'   => $body,
            'footer' => self::getFooter()
        ]);
    }
}