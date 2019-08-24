<?php

use app\models\Venda;
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
        $colunas[] = [
            'attribute' => 'dt_venda',
            'format' => 'date',
            'footer' => 'Total'
        ];

        //$colunas[] = 'pagamentos:currency';
    } elseif ($por_mes) {
        $colunas[] = [
            'attribute' => 'dt_venda',
           
            'footer' => 'Total'
        ];

        //$colunas[] = 'pagamentos:currency';
    }

    if ($por_produto) {
        $colunas[] = 'nome';
        $colunas[] = [
            'attribute' => 'quantidade',
            'format' => 'currency',
            'contentOptions' => ['style' => 'text-align:right'],
            'footer' => Yii::$app->formatter->asCurrency(Venda::getTotal($dataProvider->models, 'quantidade')),
        ];

        $colunas[] = [
            'attribute' => 'unidade_medida',
            'footer' => ''
        ];
    }

    $colunas[] = [
        'attribute' => 'pagamentos',
        'format' => 'currency',
        'contentOptions' => ['style' => 'text-align:right'],
        'footer' => Yii::$app->formatter->asCurrency(Venda::getTotal($dataProvider->models, 'pagamentos')),
    ];
    $colunas[] = [
        'attribute' => 'pagamentos_liquido',
        'format' => 'currency',
        'contentOptions' => ['style' => 'text-align:right'],
        'footer' => Yii::$app->formatter->asCurrency(Venda::getTotal($dataProvider->models, 'pagamentos_liquido')),
    ];


    echo GridView::widget([
        'showFooter' => true,
        'footerRowOptions'=>['style'=>'font-weight:bold;text-align:right;text-decoration: underline;'],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => $colunas
    ]);
    ?>


</div>
