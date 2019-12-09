<?php

use app\models\Produto;
use kartik\editable\Editable;
use kartik\number\NumberControl;
use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\Pjax;

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
        <h1 class="panel-title"> Formas de Venda </h1>

    </div>
    <div class="panel-body" style="padding: 5px">
        <div class="venda-create">
            <p>

                <?php
                echo ModalAjax::widget([
                    'id' => 'createCompany',
                    'header' => 'Adicionar Forma de Venda',
                    'toggleButton' => [
                        'label' => 'Adicionar Forma de Venda',
                        'class' => 'btn btn-primary pull-right'
                    ],
                    'url' => Url::to(['create-preco', 'pk_produto' => $model->pk_produto]), // Ajax view with form to load
                    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                    'size' => ModalAjax::SIZE_LARGE,
                    'options' => ['class' => 'header-primary'],
                    'autoClose' => true,
                    'pjaxContainer' => '#grid-company-pjax',
                ]);


                echo ModalAjax::widget([
                    'id' => 'createCompany',
                    'selector' => '.update-preco', // all buttons in grid view with href attribute
                    'options' => ['class' => 'header-primary'],
                    'pjaxContainer' => '#grid-company-pjax',
                    'events' => [
                        ModalAjax::EVENT_MODAL_SHOW => new JsExpression("
            function(event, data, status, xhr, selector) {
                selector.addClass('warning');
            }
       "),
                        ModalAjax::EVENT_MODAL_SUBMIT => new JsExpression("
            function(event, data, status, xhr, selector) {
             
                if(status){
                     location.reload();                  
                    $(this).modal('toggle');
                }
            }
        "),
                        ModalAjax::EVENT_MODAL_SHOW_COMPLETE => new JsExpression("
            function(event, xhr, textStatus) {
                if (xhr.status == 403) {
                    $(this).modal('toggle');
                    alert('You do not have permission to execute this action');
                }
            }
        "),
                        ModalAjax::EVENT_MODAL_SUBMIT_COMPLETE => new JsExpression("
            function(event, xhr, textStatus) {
                if (xhr.status == 403) {
                    $(this).modal('toggle');
                    alert('You do not have permission to execute this action');
                }
            }
        ")
                    ]
                ]);
                ?>

            </p>


            <?php
            Pjax::begin([
                'id' => 'grid-company-pjax',
                'timeout' => 5000,
            ]);

            echo
            GridView::widget([
                'dataProvider' => $dataProviderPreco,
                'filterModel' => $searchModelPreco,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'denominacao',
                    [
                        'contentOptions' => ['style' => 'text-align:right;'],
                        'attribute' => 'preco',
                        'value' => function($model) {
                            return Editable::widget([
                                        // 'inputType' => Editable::INPUT_HIDDEN,
                                        'model' => $model,
                                        'value' => Yii::$app->formatter->asCurrency($model->preco),
                                        'asPopover' => true,
                                        'size' => 'md',
                                        'name' => 'aux',
                                        'inputType' => Editable::INPUT_HIDDEN,
                                        'formOptions' => ['action' => ['altera-preco', 'id' => $model->pk_preco, 'grid' => 'tela_produto']],
                                        'beforeInput' => NumberControl::widget([
                                            'name' => 'preco',
                                            'options' => ['id' => 'preco-dip-' . $model->pk_preco],
                                            'value' => Yii::$app->formatter->asCurrency($model->preco),
                                            'maskedInputOptions' => [
                                                'prefix' => '',
                                                'suffix' => '',
                                                'allowMinus' => false,
                                                'digits' => 2,
                                            ],
                                        ])
                                            ]
                            );
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'quantidade',
                        'value' => function($model) {
                            return Yii::$app->formatter->asDecimal($model->quantidade, 3);
                        },
                        'contentOptions' => ['style' => 'text-align:right;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Ações',
                        'headerOptions' => ['style' => 'color:#337ab7'],
                        'template' => '{barcode} {update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-preco', 'pk_preco' => $model->pk_preco], [
                                            'class' => 'update-preco',
                                            'title' => 'Atualizar',
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
            Pjax::end();
            ?>
        </div>
    </div>
</div>

