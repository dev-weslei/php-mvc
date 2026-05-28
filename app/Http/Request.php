<?php

namespace App\Http;

class Request {

    /**
     * Instância de Router
     *
     * @var Router
     */
    private $router;

    /**
     * Método HTTP da Requisição
     *
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página (rota)
     *
     * @var string
     */
    private $uri;

    /**
     * Parametros da URL ($_GET)
     *
     * @var array
     */
    private $queryParams = [];

    /**
     * Variaveis enviadas via POST ($_POST)
     *
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da Requisição
     *
     * @var array
     */
    private $headers = [];

    /**
     * Método responsável por inicializar a classe
     * @param Router $router
     */
    public function __construct($router) {
        $this->router      = $router;
        $this->queryParams = $_GET ?? [];
        $this->postVars    = $_POST ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
    }

    /**
     * Método responsável por definir a URI (sem queryParams)
     *
     */
    private function setUri() {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    // Métodos acessores
    public function getRouter() {
        return $this->router;
    }

    public function getHttpMethod() {
        return $this->httpMethod;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getQueryParams() {
        return $this->queryParams;
    }

    public function getPostVars() {
        return $this->postVars;
    }
    
    public function getHeaders() {
        return $this->headers;
    }

}