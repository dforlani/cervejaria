<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

    <?php // echo $this->render('_search', ['model' => $searchModel]);   ?>

    <?=
    GridView::widget([
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
            ['class' => 'yii\grid\SerialColumn'],
            // 'pk_produto',
            'nome',
            [
                'attribute' => 'estoque_inicial',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right'],
            ],
            [
                'attribute' => 'estoque_vendido',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right'],
            ],
            [
                'label' => 'Estoque Atual',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right'],
                'value' => function($model) {
                    return $model->estoque_inicial - $model->estoque_vendido;
                }
            ]
            ,
            [
                'attribute' => 'estoque_minimo',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right'],
            ],
            'unidadeMedida.unidade_medida',
            'dt_fabricacao:date',
            'dt_vencimento:date',
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
