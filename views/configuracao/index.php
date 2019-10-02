<?php

use app\models\Configuracao;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $configuracoes Configuracao */


$this->title = 'Configuração';
$this->params['breadcrumbs'][] = $this->title;
?>

<form>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo '<label class="control-label">Gerar PDF pra todas as vendas?</label>';
            echo SwitchInput::widget(['name' => 'conf_pdf_todas_paginas', 'value' => $configuracoes['pdf_todas_paginas']->valor]);
            ?>
            Ele será gravado automaticamente na pasta <b>cervejaria/pdf/vendas</b>

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <?php
            echo '<label class="control-label">Mostrar botão de Fiado nas Vendas?</label>';
            echo SwitchInput::widget(['name' => 'is_mostrar_botao_fiado', 'value' => $configuracoes['is_mostrar_botao_fiado']->valor]);
            ?>
        </div>
    </div>
    <?= Html::submitButton('Salvar', ['name' => 'salvar', 'value' => '1', 'class' => 'btn btn-success']) ?>

</form>

