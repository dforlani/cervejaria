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

    <p>
        <?= Html::a('Create Venda', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pk_venda',
            'fk_cliente',
            'fk_comanda',
            'fk_usuario_iniciou_venda',
            'fk_usuario_recebeu_pagamento',
            //'valor_total',
            //'desconto',
            //'valor_final',
            //'estado',
            //'dt_venda',
            //'dt_pagamento',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
