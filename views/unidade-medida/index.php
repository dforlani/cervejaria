<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UnidadeMedidaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Unidade Medidas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unidade-medida-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Nova Unidade de Medida', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'unidade_medida',
            'pk_unidade_medida',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
