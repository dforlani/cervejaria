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

<form >
    <div class="row" >
        <div class="col-md-3" style="height: 40px">
            <label class="control-label">Gerar PDF pra todas as vendas?</label>
            <?php
            echo SwitchInput::widget(['name' => 'conf_pdf_todas_paginas', 'value' => $configuracoes['pdf_todas_paginas']->valor]);
            ?>
            Ele será gravado automaticamente na pasta <b>cervejaria/pdf/vendas</b>
        </div>
        <div class="col-md-3" style="height: 40px">
            <label class="control-label">Mostrar botão de Fiado nas Vendas?</label>
            <?php
            echo SwitchInput::widget(['name' => 'is_mostrar_botao_fiado', 'value' => $configuracoes['is_mostrar_botao_fiado']->valor]);
            ?>
        </div>
        <div class="col-md-3">
            <label class="control-label">Tempo em minutos pro backup automático</label>
            <?php
            echo Html::input('text', 'tempo_em_minutos_para_backup_automatico', $configuracoes['tempo_em_minutos_para_backup_automatico']->valor);
             
            ?>
            Se valor igual a <b> Zero</b> não será gerado o backup.
        </div>
    </div>
    <br><br><br><br><br><br>

    <?= Html::submitButton('Salvar', ['name' => 'salvar', 'value' => '1', 'class' => 'btn btn-success']) ?>



</form>

