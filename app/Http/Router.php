<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router {
    
    /**
     * URL completa da aplicação (raiz do projeto)
     *
     * @var string
     */
    private $url = "";

    /**
     * Define o prefixo comum de todas as rotas 
     * 
     * @var string
     */
    private $prefix = "";

    /**
     * Indíce de rotas (irá armazenar todas as rotas da aplicação)
     *
     * @var array
     */
    private $routes = [];

    /**
     * Instância de Request
     *
     * @var Request
     */
    private $request;

    /**
     * Método responsável por inicializar a classe
     *
     * @param string $url
     */
    public function __construct($url) {
        $this->request = new Request();
        $this->url     = $url;
        $this->setPrefix();
        
    }

    /**
     * Método responsável por definir o Prefixo de todas as rotas.
     *
     */
    private function setPrefix(){
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     *
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params = []) {
        foreach ($params as $key => $value) {
            if($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // VARIAVEIS GET DA ROTA
        $params['variables'] = [];

        // EXPRESSÃO REGULAR PARA VALIDAR AS VARIAVEIS DA ROTA
        $patternVariable = '/{(.*?)}/';
        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // EXPRESSÃO REGULAR PARA VALIDAR A ROTA
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';
       
        // ADICIONA A ROTA DENTRO DO OBJETO INSTANCIADO
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método responsável por definir uma rota de GET
     *
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = []){
        $this->addRoute('GET', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de POST
     *
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = []){
        $this->addRoute('POST', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de PUT
     *
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = []){
        $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método responsável por definir uma rota de DELETE
     *
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = []){
        $this->addRoute('DELETE', $route, $params);
    }


    /**
     * Método responsável por retornar a URI desconsiderando o Prefixo.
     *
     * @return string
     */
    private function getUri() {
        // URI DA REQUEST
        $uri = $this->request->getUri();
  
        // FATIA A URI COM O PREFIXO        
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     *
     * @return array
     */
    private function getRoute() {
        $uri = $this->getUri();

        //METHOD 
        $httpMethod = $this->request->getHttpMethod();

        // VALIDA AS ROTAS
        foreach ($this->routes as $patternRoute => $methods) {
            if (preg_match($patternRoute, $uri, $matches)) {
                if (isset($methods[$httpMethod])) {
                    // REMOVE A PRIMEIRA POSICAO
                    unset($matches[0]);

                    // VARIAVEIS PROCESSADAS
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // RETORNO DOS PARAMETROS DA ROTA
                    return $methods[$httpMethod];
                }

                throw new Exception("Método não permitido!", 405); 
            }
        }

        throw new Exception("Rota não encontrada!", 404); 
    }

    /**
     * Método responsável por executar a rota atual
     *
     * @return Response
     */
    public function run() {
        try {
            $route = $this->getRoute();
               
            // VERIFICA O CONTROLLADOR
            if (!isset($route['controller'])) {
                throw new Exception("URL pôde ser processada!", 500);
            }

            // ARGUMENTOS DA FUNÇÃO
            $args = [];
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return call_user_func_array($route['controller'], $args);
            
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}