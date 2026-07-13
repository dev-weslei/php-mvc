<?php

namespace App\Utils\Cache;

use \Closure;

class File
{
    /**
     * Método responsável por retornar o caminho até o arquivo de Cache
     * @param string $hash
     * @return string
     */
    private static function getFilePath($hash) {
        // Diretório de Cache
        $dir = getenv('CACHE_DIR');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Retorna o caminho até o arquivo
        return $dir.'/'.$hash;
    }

    /**
     * Método responsável por guardar informações no Cache
     * @param string $hash
     * @param mixed $content
     * @return boolean
     */
    private static function storedCache($hash, $content) {
        $serialize = serialize($content);
        $cacheFile = self::getFilePath($hash);
        
        return file_put_contents($cacheFile, $serialize);
    }


    /**
     * Método responsável por retornar o conteúdo gravado no cache
     *
     * @param string $hash
     * @param integer $expiration
     * @return mixed
     */
    private static function getContentCache($hash, $expiration) {
        $cacheFile = self::getFilePath($hash);
        if (!file_exists($cacheFile)) {
            return false;
        }

        $createTime = filemtime($cacheFile);
        $diffTime = time() - $createTime;
        if ($diffTime > $expiration) {
            return false;
        }

        // Retorna o dado real - gravado no arquivo de cache
        $serialize = file_get_contents($cacheFile);
        return unserialize($serialize);
    }

    /**
     * Método responsável por obter uma informação do cache
     *
     * @param string $hash
     * @param integer $expiration
     * @param Closure $function
     * @return mixed
     */
    public static function getCache(
        $hash,
        $expiration,
        $function
    ) {
        // Verifica o conteúdo gravado
        if ($content = self::getContentCache($hash, $expiration)) {
            return $content;
        }

        $content = $function();
        self::storedCache($hash, $content);
        return $content;
    }
}
