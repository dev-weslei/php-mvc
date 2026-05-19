<?php

namespace App\Common;

class Enviroment {

    /**
     * Método responsável por carregar as váriaveis de ambiente da aplicação
     * @param string $dir (caminho absoluto da pasta onde encontra-se o arquivo .env)
     */
    public static function load($dir) {
        // VERIFICA SE O ARQUIVO .ENV EXISTE
        if (!file_exists($dir.'/.env')) {
            return false;
        }

        // DEFINE AS VÁRIAVEIS DE AMBIENTE
        $lines = file($dir.'/.env');
        foreach ($lines as $line) {
            putenv(trim($line));
        }
    }
}

