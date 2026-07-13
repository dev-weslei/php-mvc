<?php

namespace App\Http\Middleware;

use \Closure;
use \App\Http\Request;
use \App\Http\Response;
use App\Utils\Cache\File as CacheFile;

class Cache {

    /**
     * Método responsável por retornar a hash do Cache
     * @param Request $request
     * @return string
     */
    private function getHash($request) {
        // URI da rota
        $uri = $request->getRouter()->getUri();

        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?'.http_build_query($queryParams) : '';

        // Remove as barras e retorna a hash
        return rtrim('route-'.preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/')), '-');
    }

    /**
     * Método responsável por verificar se a rota atual pode realizar cache.
     *
     * @param Request $request
     * @return boolean
     */
    private function isCachable($request) { 
        // Valida o tempo de cache
        if (getenv('CACHE_TIME') <= 0) {
            return false;
        }

        // Valida método da requisição
        if ($request->getHttpMethod() != 'GET') {
            return false;
        }

        // Valida o header de cache - o cliente consegue decidir se quer o cache ou não
        $headers = $request->getHeaders();
        if (isset($headers['Cache-Control']) && $headers['Cache-Control'] == 'no-cache') {
            return false;
        }

        return true;
    }

    /**
     * Método responsável por executar o Middleware
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next) {
        if (!$this->isCachable($request)) {
            // Executa o próximo nível do Middleware
            return $next($request);
        }

        $hash = $this->getHash($request);

        echo '<pre>';
        print_r('teste');
        echo '</pre>';

        // Retorna os dados do Cache
        return CacheFile::getCache(
            $hash, 
            getenv('CACHE_TIME'), 
            function() use ($request, $next){
                return $next($request);
            }
        );
    }
}