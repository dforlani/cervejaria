<?php

use app\models\Produto;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Produto */

$this->title = 'Update Produto: ' . $model->pk_produto;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pk_produto, 'url' => ['view', 'id' => $model->pk_produto]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="produto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
<br>
<div class="border-success">
    <h1> Preços </h1>
    <p>
        <?= Html::a('Create Preco', ['create-preco', 'pk_produto' => $model->pk_produto], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?=
    GridView::widget([
        'dataProvider' => $dataProviderPreco,
        'filterModel' => $searchModelPreco,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
//            'pk_preco',
//            'fk_produto',
            'denominacao',
            'preco',
            'quantidade',
            // ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Actions',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                    'title' => Yii::t('app', 'lead-view'),
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-preco', 'pk_preco' => $model->pk_preco], [
                                    'title' => Yii::t('app', 'lead-update'),
                        ]);
                    },

                    'delete' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-preco', 'pk_preco' => $model->pk_preco], [
                                    'class' => '',
                                    'data' => [
                                        'confirm' => 'Tem certeza que deseja remover este preço?',
                                        'method' => 'post',
                                    ],
                        ]);
                    }
                ],
            ],
        ],
    ]);
    ?>
</div>