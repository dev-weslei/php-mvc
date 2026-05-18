<?php

namespace App\Http;

class Request {

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

    public function __construct() {
        $this->queryParams = $_GET ?? [];
        $this->postVars    = $_POST ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri         = $_SERVER['REQUEST_URI'] ?? '';
    }

    // Métodos acessoresd
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