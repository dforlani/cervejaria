<?php

use app\models\ProdutoSearch;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\widgets\SwitchInput;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProdutoSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Cervejas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="produto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nova Cerveja', ['create-cerveja'], ['class' => 'btn btn-success']) ?>&nbsp;&nbsp;<?= Html::a('Gerar PDF de Códigos de Barra', ['gerar-pdf'], ['class' => 'btn btn-warning']) ?>
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
                'class' => 'kartik\grid\EditableColumn',
                 'filter'=>[1=>'Sim', 0=>'Não'],
                'attribute' => 'is_vendavel',
                'value' => function($model){
                    return Yii::$app->formatter->asBoolean($model->is_vendavel);
                },
                'editableOptions' => function ($model, $key, $index) {
                    return [
                    'header' => 'Pra Vender?',
                    'inputType' => Editable::INPUT_SWITCH,
                      'formOptions' => ['action' => ['altera-produto-is-vendavel', 'id' => $model->pk_produto]],
                ];
                            
                },
                'hAlign' => 'right',
                'vAlign' => 'middle',
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
                'attribute' => 'auxHasPromocao',
                'label' => 'Promoção?',
                'value' => function($model) {
                    return $model->hasPromocaoAtiva();
                },
                'filter' => [1 => 'Sim', 0 => 'Não']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-cerveja', 'id' => $model->pk_produto], [
                                    'class' => 'update',
                                    'title' => 'Atualizar',
                        ]);
                    },
                ],
            ],
        ],
    ]);
    ?>


</div>
