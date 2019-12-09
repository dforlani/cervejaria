<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProdutoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Produtos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="produto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Novo Produto', ['create'], ['class' => 'btn btn-success']) ?>&nbsp;&nbsp;<?= Html::a('Gerar PDF de Códigos de Barra', ['gerar-pdf'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?=
    GridView::widget([
        'options' => ['style' => 'text-align:right;font-size:12px;'],
        'headerRowOptions' => ['style' => 'text-align:right;font-size:12px;'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $index, $widget, $grid) {

            if ($model->estoque_inicial - $model->estoque_vendido <= $model->estoque_minimo) {
                return ['class' => 'danger'];
            } else {
                return [];
            }
        },
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
                'attribute' => 'nome',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
           
            [
                'attribute' => 'estoque_inicial',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'estoque_vendido',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'label' => 'Estoque Atual',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
                'value' => function($model) {
                    return $model->estoque_inicial - $model->estoque_vendido;
                }
            ],
            [
                'attribute' => 'estoque_minimo',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'unidadeMedida.unidade_medida',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'dt_fabricacao',
                'format' => 'date',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'dt_vencimento',
                'format' => 'date',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{update} {delete}',
            ],
        ],
    ]);
    ?>


</div>
