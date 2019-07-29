<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ComandaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */ 

$this->title = 'Comandas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comanda-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nova Comanda', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
          
            'numero',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{barcode} {update} {delete}',
                'buttons' => [
                    
                    'barcode' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-barcode"></span>', ['codigo-barras', 'id' => $model->pk_comanda], [
                                    'title' => 'Gerar Código Barras'
                        ]);
                    },                   
                ],
            ],
        ],
    ]);
    ?>


</div>
