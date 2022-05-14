<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VendaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vendas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venda-index">

    <h1><?= Html::encode($this->title) ?></h1>    

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
         'rowOptions' => function ($model, $index, $widget, $grid) {

            if ($model->estado == 'aberta') {
                return ['class' => 'danger'];
            } elseif ($model->estado == 'fiado') {
                return  ['class' => 'warning'];
            }else{
             
                return  ['class' => 'success'];
            
            }
        },
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            // 'pk_venda',
            'comanda.numero',
            [
                'attribute' => 'aux_nome_cliente',
                'label' => 'Cliente'
            ],
            //'comanda.numero',
            //'fk_usuario_iniciou_venda',
            // 'fk_usuario_recebeu_pagamento',
            'valor_total:currency',
            'desconto:currency',
            'valor_final:currency',
            'estado',
            'dt_venda:datetime',
            'dt_pagamento:datetime',
            //'dt_pagamento',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['venda', 'id' => $model->pk_venda], [
                                    'title' => Yii::t('app', 'lead-update'),
                        ]);
                    },
                ],
            ],
        ],
    ]);
    ?>


</div>
