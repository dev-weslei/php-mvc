<?php

namespace App\Controller\admin;

use App\Utils\View;
use App\Http\Request;
use App\Model\Entity\Testimony;
use WilliamCosta\DatabaseManager\Pagination;

class Testimonies extends Template {
    /**
     * Método responsável por renderizar a view de Home do painel de adminstração
     *
     * @param Request $request
     * @return string
     */
    public static function getTestimonies($request) {
        $content = View::render('admin/modules/testimonies/index', [
            'itens'      => self::getTestimonyItens($request, $obPagination),
            'pagination' => parent::getPagination($request, $obPagination),
            'status'     => self::getStatus($request)
        ]);

        return parent::getPanel('Home > WDEV', $content, 'testimonies');
    }

    /**
     * Método responsável por obter a renderização dos itens para a página
     * @param Request $request
     * @param Pagination $obPagination
     * @return string
     */
    private static function getTestimonyItens($request, &$obPagination) {
        $itens = '';

        $quantidadeTotalDepoimentos = Testimony::getTestimonies(null, null, null, 'COUNT(*) as qtd')
                                        ->fetchObject()
                                        ->qtd;

        $paginaAtual = $request->getQueryParams();
        $paginaAtual = $paginaAtual['page'] ?? 1;

        // instância de paginação
        $obPagination = new Pagination($quantidadeTotalDepoimentos, $paginaAtual, 5);
        $results = Testimony::getTestimonies(null, 'id ASC', $obPagination->getLimit());

        // RENDERIZA O ITEM
        while ($obTestimony = $results->fetchObject(Testimony::class)) {
            $itens .= View::render('admin/modules/testimonies/item', [
                'id'    => $obTestimony->id,
                'nome'  => $obTestimony->nome, 
                'texto' => $obTestimony->mensagem,
                'data'  => date('d/m/Y H:i:s', strtotime($obTestimony->data_criacao))
            ]);
        }

        // Retorna a lista de depoimentos
        return $itens;
    }

    /**
     * Método responsável por retornar o formulário de cadastro de Depoimento
     * @return string
     */
    public static function getNewTestimony() {
        $content = View::render('admin/modules/testimonies/form', [
           'title'    => 'Cadastrar depoimento',
           'nome'     => '',
           'mensagem' => '',
           'status'   => ''
        ]);

        return parent::getPanel(
            'Cadastrar depoimento > WDEV', 
            $content, 
            'testimonies'
        );
    }

    /**
     * Método responsável por cadastro Depoimento
     * @param Request $request
     */
    public static function setNewTestimony($request) {
        $postVars = $request->getPostVars();

        $obTestimony           = new Testimony();
        $obTestimony->nome     = $postVars['nome'];
        $obTestimony->mensagem = $postVars['mensagem'];
        $obTestimony->cadastrar();

        // Redireciona o usuário
        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=created');
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return void
     */
    private static function getStatus($request) {
        $queryParams = $request->getQueryParams();
        if (!isset($queryParams['status'])) {
            return '';
        };

        // Mensagens de status
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Depoimento criado com sucesso!');
                break;
            case 'updated':
                return Alert::getSuccess('Depoimento atualizado com sucesso!');
                break;
            case 'deleted':
                return Alert::getSuccess('Depoimento excluído com sucesso!');
                break;
        }
    }

    /**
     * Méotodo responsável por retornar o formulário de edição de depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getEditTestimony($request, $id) {
        $obTestimony = Testimony::getTestimonyById($id);
        
        // Valida a instância
        if (!$obTestimony instanceof Testimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/form', [
           'title'    => 'Editar depoimento',
           'nome'     => $obTestimony->nome,
           'mensagem' => $obTestimony->mensagem,
           'status'   => self::getStatus($request)
        ]);

        return parent::getPanel(
            'Editar depoimento > WDEV', 
            $content, 
            'testimonies'
        );
    }

    /**
     * Méotodo responsável por persistir as alterações das informações de um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setEditTestimony($request, $id) {
        $obTestimony = Testimony::getTestimonyById($id);
        
        // Valida a instância
        if (!$obTestimony instanceof Testimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $postVars = $request->getPostVars();
        $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
        $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem; 
        $obTestimony->atualizar();

        $request->getRouter()->redirect('/admin/testimonies/'.$obTestimony->id.'/edit?status=updated');
    }

     /**
     * Méotodo responsável por renderizar a view de exclusão de um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function getDeleteTestimony($request, $id) {
        $obTestimony = Testimony::getTestimonyById($id);
        
        // Valida a instância
        if (!$obTestimony instanceof Testimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $content = View::render('admin/modules/testimonies/delete', [
           'nome'     => $obTestimony->nome,
           'mensagem' => $obTestimony->mensagem,
        ]);

        return parent::getPanel(
            'Excluir depoimento > WDEV', 
            $content, 
            'testimonies'
        );
    }


   /**
     * Méotodo responsável por excluir um depoimento
     * @param Request $request
     * @param integer $id
     * @return string
     */
    public static function setDeleteTestimony($request, $id) {
        $obTestimony = Testimony::getTestimonyById($id);
        // Valida a instância
        if (!$obTestimony instanceof Testimony) {
            $request->getRouter()->redirect('/admin/testimonies');
        }

        $obTestimony->excluir();

        $request->getRouter()->redirect('/admin/testimonies?status=deleted');
    }
}