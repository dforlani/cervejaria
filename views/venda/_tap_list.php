<?php

use app\models\Preco;
use kartik\number\NumberControl;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
?>




<?php


echo GridView::widget([
    'dataProvider' => $tapListProvider,
    'layout' => '{items}{pager}{summary}',
    'options' => ['style' => 'font-size:14px;'],
    'columns' => [
         [
            'label' => '#',
            'value' => 'pos_tap_list',
        ],
        [
            'label' => 'Produto',
            'value' => 'nomeProdutoPlusDenominacaoSemBarras',
        ],
        [
            'attribute' => 'preco',
            'format' => 'currency',
            'contentOptions' => ['style' => 'text-align:right'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'template' => '{adiciona}',
            'buttons' => [
                'adiciona' => function($url, $model_preco) use ($model) {
                    
                    return Html::a('<span  class="glyphicon glyphicon-plus btn btn-default"></span>', ['adiciona-item', "pk_venda" => $model->pk_venda, 'pk_preco' => $model_preco->pk_preco], [                                                              
                        'accessKey' => $model_preco->pos_tap_list,
                        'title' => 'Adicionar 1 Produto na Venda',
                        
                                'data' => [
                                    'method' => 'post',
                                ],
                    ]);
                }
            ],
        ],
    ],
]);
