<?php

use app\models\UsuarioQuery;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UsuarioQuery */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Usuario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'login',
            'nome',
            'sobrenome',
            'senha',
            [
                'attribute' => 'papel',
                'filter' => false,
                'format' => 'raw',
                'value' => function($model) {
                    return implode(',', ArrayHelper::map($model->papeis, 'item_name', 'item_name'));
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>


</div>
