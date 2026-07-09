<?php

namespace App\Http;

use App\Model\Entity\User;

class Request
{

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
     * Classe de usuário
     *
     * @var User
     */
    private $user;

    /**
     * Método responsável por inicializar a classe
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->router      = $router;
        $this->queryParams = $_GET ?? [];
        $this->headers     = getallheaders();
        $this->httpMethod  = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();
    }

    /**
     * Método responsável por retornar a instância de User
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Método responsável por definir a instância de User
     * @param User $obUser
     */
    public function setUser($obUser)
    {
        $this->user = $obUser;
    }

    /**
     * Método responsável por definir as váriaveis do POST
     */
    private function setPostVars()
    {
        if ($this->httpMethod == 'GET') {
            return false;
        }

        $this->postVars = $_POST ?? [];

        // Valida o POST JSON
        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    /**
     * Método responsável por definir a URI (sem queryParams)
     *
     */
    private function setUri()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    // Métodos acessores
    public function getRouter()
    {
        return $this->router;
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getQueryParams()
    {
        return $this->queryParams;
    }

    public function getPostVars()
    {
        return $this->postVars;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
