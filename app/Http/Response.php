<?php

namespace App\Http;

class Response {
    private $httpCode = 200;
    private $headers = [];

    /**
     * Tipo de conteúdo que está sendo retornado
     *
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo do Response (tipo de retorno de acordo com o tipo de conteúdo)
     *
     * @var mixed
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     *
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType = 'text/html') {
        $this->httpCode = $httpCode;
        $this->content  = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o Content-type do Response
     *
     * @param $string $content
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adiconar um registro no cabeçalho de Response
     *
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
    }

    /**
     * Método responsável por enviar os headers para o navegador.
     *
     */
    private function sendHeaders(){
        http_response_code($this->httpCode);
        foreach ($headers as $key => $value) {
            header($key.": ".$value);
        }
    }

    /**
     * Método responsável por enviar a resposta para o cliente.
     *
     */
    public function sendResponse() {
        $this->sendHeaders();
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                break;
        }
    }
}