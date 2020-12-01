<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntradaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Entradas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entrada-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Entrada', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pk_entrada',
            'fk_usuario',
            'fk_produto',
            'quantidade',
            'dt_entrada',
            //'custo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
