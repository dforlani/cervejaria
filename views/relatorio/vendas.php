<?php

use app\models\VendaSearch;
use kartik\widgets\SwitchInput;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel VendaSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Relatórios de Vendas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venda-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <form>
    <div class="row">
        <div class="col-md-3">
            <?php
            echo '<label class="control-label">Por dia</label>';
            echo SwitchInput::widget(['name' => 'por_dia', 'value' => $por_dia]);
            ?>
        </div>

        <div class="col-md-3">
            <?php
            echo '<label class="control-label">Por Mês</label>';
            echo SwitchInput::widget(['name' => 'por_mes', 'value' => $por_mes]);
            ?>
        </div>

        <div class="col-md-3">
            <?php
            echo '<label class="control-label">Por Produto</label>';
            echo SwitchInput::widget(['name' => 'por_produto', 'value' => $por_produto]);
            ?>

        </div>

        <div class="col-md-3">
            <?php
            echo '<label class="control-label">Apenas Vendas Pagas</label>';
            echo SwitchInput::widget(['name' => 'apenas_vendas_pagas', 'value' => $apenas_vendas_pagas]);
            ?>
        </div>
    </div>

        <?= Html::submitButton('<u>G</u>erar', ['accesskey' => 'g', 'class' => 'btn btn-success']) ?>
    </form><br>

    <?php
    $colunas = [];
    if ($por_dia) {
        $colunas[] = 'dt_venda:date';
        $colunas[] = 'valor_somatorio:currency';
    } elseif ($por_mes) {
        $colunas[] = 'dt_venda';
        $colunas[] = 'valor_somatorio:currency';
    }

    if ($por_produto) {
        $colunas[] = 'nome';
        $colunas[] = 'quantidade';
        $colunas[] = 'unidade_medida';
    }

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $colunas
    ]);
    ?>


</div>
