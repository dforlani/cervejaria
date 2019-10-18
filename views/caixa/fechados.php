<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PapelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Caixas Fechados';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="papel-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'dt_abertura:datetime',
            'dt_fechamento:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{reabrir} {visualizar}',
                'buttons' => [
                    'reabrir' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['reabrir', 'id' => $model->pk_caixa], [
                                    'title' => Yii::t('app', 'Reabrir Caixa'),
                        ]);
                    },
                    'visualizar' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['visualizar', 'id' => $model->pk_caixa], [
                                    'title' => Yii::t('app', 'Visualizar Caixa'),
                        ]);
                    },
                ],
            ],
        ],
    ]);
    ?>


</div>
