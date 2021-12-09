<?php

use app\models\Produto;
use app\models\ProdutoSearch;
use kartik\editable\Editable;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProdutoSearch */
/* @var $dataProvider ActiveDataProvider */

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
//        'rowOptions' => function ($model, $index, $widget, $grid) {
//            if ($model->getEstoqueTotal() - $model->estoque_vendido <= $model->estoque_minimo) {
//                return ['class' => 'danger'];
//            } else {
//                return [];
//            }
//        },
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
                'label' => 'Lote Ativo',
                'value' => function($model) {
                    return $model->temLoteAtivo();
                },
                'format' => 'boolean',
                'contentOptions' => function ($model, $key, $index, $column) {
                    return ['style' => 'text-align:right;font-size:12px;'
                        . 'background-color:' . ($model->temLoteAtivo() ? '' : '#ff6666')
                        . ';color:' . ($model->temLoteAtivo() ? '' : 'white')
                    ];
                },
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'is_vendavel',
                'filter' => [1 => 'Sim', 0 => 'Não'],
                'value' => function($model) {
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
                'attribute' => 'estoqueTotalVendido',
                'format' => 'currency',
                'header' => 'Total Vendido',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'estoqueVendidoLoteAtivo',
                'format' => 'currency',
                'header' => 'Vendido do Lote Ativo',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'estoqueDisponivelLoteAtivo',
                'format' => 'currency',
                'header' => 'Dispon. Lote Ativo',
                'contentOptions' => function ($model, $key, $index, $column) {
                    return ['style' => 'text-align:right;font-size:12px;'
                        . 'background-color:' . ($model->getEstoqueMinimodoLoteAtivoAtingido() ? '#ff6666' : '')
                        . ';color:' . ($model->getEstoqueMinimodoLoteAtivoAtingido() ? 'white' : '')
                    ];
                },
            ],
//            [
//                'attribute' => 'estoque_inicial',
//                'format' => 'currency',
//                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
//            ],
//            [
//                'attribute' => 'estoque_vendido',
//                'format' => 'currency',
//                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
//            ],
//            [
//                'label' => 'Estoque Atual',
//                'format' => 'currency',
//                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
//                'value' => function($model) {
//                    return $model->getEstoqueTotal() - $model->estoque_vendido;
//                }
//            ],
            [
                'attribute' => 'estoque_minimo',
                'format' => 'currency',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
            [
                'attribute' => 'unidadeMedida.unidade_medida',
                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
            ],
//            [
//                'attribute' => 'dt_fabricacao',
//                'format' => 'date',
//                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
//            ],
//            [
//                'attribute' => 'dt_vencimento',
//                'format' => 'date',
//                'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
//            ],
            [
                'attribute' => 'auxHasPromocao',
                'label' => 'Promoção?',
                'value' => function($model) {
                    return $model->hasPromocaoAtiva();
                },
                'filter' => [1 => 'Sim', 0 => 'Não']
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'tipo_produto',
                'filter' => Produto::getTiposProdutos(),
//                'value' => function($model) {
//                    return Yii::$app->formatter->asBoolean($model->is_vendavel);
//                },
                'editableOptions' => function ($model, $key, $index) {
                    return [
                        'header' => 'Tipo',
                        'inputType' => Editable::INPUT_DROPDOWN_LIST,
                        'data'=>Produto::getTiposProdutos(),
                        'formOptions' => ['action' => ['altera-produto-tipo', 'id' => $model->pk_produto]],
                    ];
                },
                'hAlign' => 'right',
                'vAlign' => 'middle',
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
