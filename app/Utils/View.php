<?php

namespace App\Utils;

class View {
    /**
     * Váriaveis padrão da View
     * @var array
     */
    private static $vars = [];

    /**
     * Método responsável por renderizar uma view genérica
     * @param string $view
     */
    private static function getContentView($view) {
        $file = __DIR__ . "/../../resources/view/".$view .".html";
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /** 
     * Método responsável por definir os dados iniciais da Classe
     */
    public static function init($vars = []) {
        self::$vars = $vars;
    }

    /**
     * Método responsável por retornar uma view renderizada
     * @param string $view
     * @param array $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars = []) {

        $contentView = self::getContentView($view);

        // Merge das variavies do usuário com os padrões da view
        $vars = array_merge(self::$vars, $vars);
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return "{{" . $item . "}}";
        }, $keys);

        // $keys (chaves com formato de replace), valores para ser inseridos, string onde será realizada a substituição
        return str_replace(
            $keys, 
            array_values($vars), 
            $contentView
        );
    }
}