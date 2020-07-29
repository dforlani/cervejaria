<?php

use app\models\Preco;
use app\models\ProdutoSearch;
use kartik\editable\Editable;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel ProdutoSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Tap List';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="produto-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php $form = ActiveForm::begin(); ?>  
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4" style="">  

                <?php
                echo $form->field($model, 'pk_preco')->widget(Select2::classname(), [
                    'data' => Preco::getArrayProdutosPrecos(),
                    'options' => ['placeholder' => 'Selecione um Produto'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Produto');
                ?>

            </div>
            <div class='col-md-3'>
                <br>
                <?= Html::submitButton('Incluir', ['id' => 'btn_incluir', 'class' => 'btn btn btn-primary']) ?>
            </div>
        </div>
            <?= $form->errorSummary($model); ?>
    </div>

    <?php ActiveForm::end(); ?>

</p>

<?=
GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
 [//coluna invisível necessário pra não estragar a lista de pedidos pelo app, não descobri o motivo
 'visible'=>false,
                'class' => 'kartik\grid\ExpandRowColumn',
                'width' => '50px',
                'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                // uncomment below and comment detail if you need to render via ajax
                // 'detailUrl'=>Url::to(['/site/book-details']),
                'detail' => function ($model, $key, $index, $column) {
                    return Yii::$app->controller->renderPartial('empty', ['model' => $model]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true
            ],
        [
            'class' => 'kartik\grid\EditableColumn',
            'attribute' => 'pos_cardapio',
            'editableOptions' => [
                'header' => 'Posição',
                'inputType' => Editable::INPUT_SPIN,
                'options' => ['pluginOptions' => ['min' => 0, 'max' => 5000]],
                 'pluginEvents' => [
                        "editableSuccess" => "function(event, val, form, data) { console.log('Successful submission of value ' + val); "
                    . " location.reload(); "
                     . "}",
 
                    ],
            ],
            'hAlign' => 'right',
            'vAlign' => 'middle',
            'width' => '100px',
            'format' => ['decimal', 0],
            'pageSummary' => true,
          
        ],
        [
            'attribute' => 'nomeProdutoPlusDenominacaoSemBarras',
            'label' => 'Produto'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Ações',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['remover-tap-list', 'id' => $model->pk_preco], [
                                'title' => Yii::t('app', 'Remover da Tap list'),
                    ]);
                },
            ],
        ],
    ],
]);
?>
<b>O número da posição utilizado poderá ser utilizado como tecla de atalho na tela de Vendas. Use Alt + número para adicionar. Só funciona para números menores que 10.</b>

</div>
