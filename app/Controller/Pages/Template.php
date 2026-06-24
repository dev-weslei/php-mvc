<?php

namespace App\Controller\Pages;
use App\Utils\View;
use App\Http\Request;
use WilliamCosta\DatabaseManager\Pagination;

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

    /**
     * Método responsável por renderizar o layout de paginação
     *
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination) {

        $pages = $obPagination->getPages();
        if (count($pages) <= 1) {
            return '';
        }
   
        // URL atual sem GETS - http://localhost:8000/php-mvc/depoimentos
        $url = $request->getRouter()->getCurrentUrl();

        // RENDERIZA OS LINKS
        $links = '';
        $queryParams = [];
        foreach ($pages as $page) {
            $queryParams['page'] = $page['page'];
            $link = $url.'?'.http_build_query($queryParams);
            // RENDERIZAÇÃO DA VIEW
            $links .= View::render('pages/pagination/link', [
                'page'   => $page['page'],
                'link'   => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        // RENDERIZA BOX DE PAGINAÇÃO
        return View::render('pages/pagination/box', [
            'links'  => $links,
        ]);
    }

    /**
     * Metodo responsável por renderizar a estrutura padrão do template
     *
     * @param string $title
     * @param string $body
     */
    public static function getTemplate($title, $body) {
        return View::render('pages/template', [
            'title'  => $title,
            'header' => self::getHeader(),
            'body'   => $body,
            'footer' => self::getFooter()
        ]);
    }
}