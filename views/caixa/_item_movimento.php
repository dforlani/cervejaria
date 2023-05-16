<?php

use app\components\Somatorio;
use app\models\CaixaSearch;
use lo\widgets\modal\ModalAjax;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel CaixaSearch */
/* @var $dataProvider ActiveDataProvider */
?>

<p>


</p>    
<div class="row">
    <div class="col-sm-8">
        <?php
        Pjax::begin([
            'id' => 'grid-company-pjax',
            'timeout' => 5000,
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'showFooter' => true,
            'options' => ['style' => 'font-size:12px;'],
            'footerRowOptions' => ['style' => 'font-weight:bold;text-align:right;text-decoration: underline;'],
            //muda a cor da linha conforme o tipo de movimento
            'rowOptions' => function ($model, $index, $widget, $grid) {

                if ($model->isAberturaCaixa()) {
                    return ['class' => 'warning'];
                } elseif ($model->isSaidaOuSangria()) {
                    return ['class' => 'danger'];
                }
            },
            'columns' => [
                [
                    'label' => 'Tipo',
                    'footer' => 'Total da Página',
                    'value' => function ($model) {
                        if ($model->isPgtoDespesa()) {
                            return "$model->tipo ($model->categoria)";
                        } else
                            return $model->tipo;
                    },
                    'contentOptions' => ['style' => 'width:200px;text-align:right'],
                ],
                [
                    'attribute' => 'valor_dinheiro',
                    'format' => 'currency',
                    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'valor_dinheiro')),
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'attribute' => 'valor_debito',
                    'format' => 'currency',
                    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'valor_debito')),
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'attribute' => 'valor_credito',
                    'format' => 'currency',
                    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'valor_credito')),
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'attribute' => 'valor_pix',
                    'format' => 'currency',
                    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'valor_pix')),
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'label' => 'Resultado',
                    'format' => 'currency',
                    'value' => function ($model) {
                        return $model->valor_credito + $model->valor_debito + $model->valor_dinheiro + $model->valor_pix;
                    },
                    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, ['valor_debito', 'valor_dinheiro', 'valor_credito', 'valor_pix'])),
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'attribute' => 'dt_movimento',
                    'format' => 'datetime',
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'header' => 'Ações',
                    'template' => '{update} {delete}',
                    'visible' => !$visualizar,
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->pk_item_caixa], [
                                'class' => 'update',
                                'title' => 'Atualizar',
                            ]);
                        },
                    ],
                    'visibleButtons' =>
                    [
                        'update' => function ($model) {
                            return !$model->isEntrada();
                        },
                        'delete' => function ($model) {
                            return !$model->isEntrada();
                        }
                    ]
                ],
            ],
        ]);
        Pjax::end();
        ?>
    </div>
    <div class="col-sm-4" style="padding-right: 35px">
        <?=
        $this->render('_resumo', [
            'itens' => $dataProvider->getModels(),
        ]);
        ?>
    </div>
</div>