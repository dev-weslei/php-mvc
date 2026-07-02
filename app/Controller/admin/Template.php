<?php 

namespace App\Controller\admin;

use App\Utils\View;
use App\Http\Request;
use WilliamCosta\DatabaseManager\Pagination;

class Template {

    /**
     * Lista de módulos disponíveis no painel de adminstração
     * @var array
     */
    private static $modules = [
        'home' => [
            'label' => 'Home',
            'link'  => URL.'/admin'
        ],
        'testimonies' => [
            'label' => 'Depoimentos',
            'link'  => URL.'/admin/testimonies'
        ],
        'users' => [
            'label' => 'Usuários',
            'link'  => URL.'/admin/users'
        ]
    ];

     /**
     * Metodo responsável por renderizar a estrutura padrão do template de Login Admin
     * @param string $title
     * @param string $body
     */
    public static function getTemplate($title, $body) {
        return View::render('admin/template', [
            'title' => $title,
            'body'  => $body
        ]);
    }

    /**
     * Método responsável por renderizar a view de menu do Painel de admistração
     * @param string $currentModule
     * @return string
     */
    private static function getMenu($currentModule) {
        $links = '';
        foreach (self::$modules as $hash => $module) {
            $links .= View::render('admin/menu/link', [
                'label' => $module['label'],
                'link'  => $module['link'],
                'current' => $hash == $currentModule ? 'text-danger' : ''
            ]);
        }
        return View::render('admin/menu/box', [
            'links' => $links
        ]);
    }   

    /**
     * Método responsável por renderizar a view do Painel de administração com conteúdos dinâmicos
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string
     */
    public static function getPanel($title, $content, $currentModule) {
        // Renderiza a view do Painel
        $contentPanel = View::render('admin/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        return self::getTemplate($title, $contentPanel);
    }

    /**
     * Método responsável por renderizar o layout de paginação
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    public static function getPagination($request, $obPagination) {
        $pages = $obPagination->getPages();
        if (count($pages) <= 1) {
            return '';
        }
   
        // URL atual sem GETS
        $url = $request->getRouter()->getCurrentUrl();

        // Renderiza a view de links do painel
        $links = '';
        $queryParams = [];
        foreach ($pages as $page) {
            $queryParams['page'] = $page['page'];
            $link = $url.'?'.http_build_query($queryParams);
            // RENDERIZAÇÃO DA VIEW
            $links .= View::render('admin/pagination/link', [
                'page'   => $page['page'],
                'link'   => $link,
                'active' => $page['current'] ? 'active' : ''
            ]);
        }

        // Renderiza o box de páginação
        return View::render('admin/pagination/box', [
            'links'  => $links,
        ]);
    }
}