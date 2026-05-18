<?php

namespace App\Utils;

class View {

    /**
     * Váriaveis padrão da View
     * @var array
     */
    private static $vars = [];

    
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
     *Retorna o conteúdo renderizado na View
     *
     * @param string $view
     * @param array $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars = []) {

        $contentView = self::getContentView($view);

        // MERGE DE VÁRIAVEIS DO USUÁRIO COM AS PADRÕES DA VIEW
        $vars = array_merge(self::$vars, $vars);

        $keys = array_keys($vars);
        // aplicando padrão de substituição em cada key no meu array
        $keys = array_map(function($item){
            return "{{" . $item . "}}";
        }, $keys);

        // $keys (chaves com formato de replace), valores para ser inseridos, string onde será realizada a substituição
        return str_replace($keys, array_values($vars), $contentView);
    }
}