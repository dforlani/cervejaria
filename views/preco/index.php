<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrecoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Precos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="preco-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Preco', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pk_preco',
            'fk_produto',
            'denominacao',
            'preco',
            'quantidade',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
