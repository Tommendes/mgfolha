<?php

/*
 * Conjunto de funções úteis a todas as classes
 */

namespace pagamentos\controllers;

use yii\helpers\Url;

/**
 * Funções da geração da folha de pagamento
 *
 * @author TomMe
 */
class ContextMenusController extends \yii\web\Controller {

    /**
     * Retorna os itens de contexto relacionados às impressões nas telas do servidor
     * @return type
     */
    public static function getItemsImpressServidor($id) {
        return [
            ['label' => 'Holerite desta folha', 'url' => ['/cad-servidores/print', 'id' => $id], 'linkOptions' => ['target' => '_blank']],
            ['label' => 'Ficha financeira analítica', 'url' => ['/cad-servidores/print', 'id' => $id, 'fileName' => 'fichaFinanceiraA'], 'linkOptions' => ['target' => '_blank']],
            ['label' => 'Ficha financeira sintética', 'url' => ['/cad-servidores/print', 'id' => $id, 'fileName' => 'fichaFinanceiraS'], 'linkOptions' => ['target' => '_blank']],
            ['label' => 'Ficha de recadastro', 'url' => ['/cad-srecadastro/print', 'id' => $id], 'linkOptions' => ['target' => '_blank']],
        ];
    }

    /**
     * Retorna os itens de contexto relacionados às impressões nas telas do servidor
     * @return type
     */
    public static function getItemsFotoServidor($model) {
        if ($model->getFalecimento() !== null):
            return [
                ['label' => 'Impossível alterar', 'url' => "#",],
            ];
        else :
            return [
                ['label' => 'Enviar foto', 'url' => ['/cad-servidores/upload', 'id' => $model->id, 'modal' => 1]],
                ['label' => 'Tirar foto', 'url' => ['/cad-servidores/photo', 'id' => $model->slug]],
            ];
        endif;
    }

}
