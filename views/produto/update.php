<?php

use app\models\Produto;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Produto */

$this->title = 'Produto ';
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
<div class='panel panel-success' >
    <div class="panel-heading">
        <h1 class="panel-title"> Preços </h1>

    </div>
    <div class="panel-body" style="padding: 5px">
        <div class="venda-create">
 <p>
        <?= Html::a('Adcionar Preço', ['create-preco', 'pk_produto' => $model->pk_produto], ['class' => 'btn btn-warning']) ?>
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
            'preco:currency',
            'quantidade:currency',
            // ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{barcode} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-preco', 'pk_preco' => $model->pk_preco], [
                                    'title' => Yii::t('app', 'lead-update'),
                        ]);
                    },
                    'barcode' => function($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-barcode"></span>', ['codigo-barras', 'pk_preco' => $model->pk_preco], [
                                    'title' => 'Gerar Código Barras'
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
                    },
                ],
            ],
        ],
    ]);
    ?>
        </div>
    </div>
</div>

