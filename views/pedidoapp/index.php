<?php

use app\models\PedidoApp;
use app\models\ProdutoSearch;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProdutoSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Pedidos pelo Aplicativo';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="produto-index">

    <h1><?= Html::encode($this->title) ?></h1>



    <?=
    GridView::widget([
        'options' => ['style' => 'text-align:right;font-size:12px;'],
        'headerRowOptions' => ['style' => 'text-align:right;font-size:12px;'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                // uncomment below and comment detail if you need to render via ajax
                // 'detailUrl'=>Url::to(['/site/book-details']),
                'detail' => function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('_expand', ['model' => $model]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true
            ],
            [
                'attribute' => 'venda.cliente.nome',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'filter' => PedidoApp::getTiposStatus(),
                'attribute' => 'status',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'dt_pedido',
                'format' => 'datetime',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{delete}',
            ],
        ],
    ]);
    ?>


</div>
