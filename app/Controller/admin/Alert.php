<?php

namespace App\Controller\admin;

use App\Utils\View;

class Alert {

    /**
     * Método responsável por retornar a mensagem de sucesso
     *
     * @param string $message
     * @return string
     */
    public static function getSuccess($message) {
        return View::render('pages/admin/alert/status', [
            'tipo'      => 'success',
            'mensagem'  => $message
        ]);
    }

    /**
     * Método responsável por retornar a mensagem de erro
     * 
     * @param string $message
     * @return string
     */
    public static function getError($message) {
        return View::render('pages/admin/alert/status', [
            'tipo'      => 'danger',
            'mensagem'  => $message
        ]);
    }
}