<?php

use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\Pjax;

?>
<div class='panel panel-danger' >
    <div class="panel-heading">
        <h1 class="panel-title"><b> Entradas</b> </h1>

    </div>
    <div class="panel-body" style="padding: 5px">
        <div class="venda-create">
            <p>

                <?php
                echo ModalAjax::widget([
                    'id' => 'createEntreda',
                    'header' => 'Adicionar Nova Entrada',
                    'toggleButton' => [
                        'label' => 'Adicionar Entrada',
                        'class' => 'btn btn-primary pull-right'
                    ],
                    'url' => Url::to(['create-entrada', 'pk_produto' => $model->pk_produto]), // Ajax view with form to load
                    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                    'size' => ModalAjax::SIZE_LARGE,
                    'options' => ['class' => 'header-primary'],
                    'autoClose' => true,
                    'pjaxContainer' => '#grid-entrada-pjax',
                ]);


                echo ModalAjax::widget([
                    'id' => 'createEntrada',
                    'selector' => '.update-entrada', // all buttons in grid view with href attribute
                    'options' => ['class' => 'header-primary'],
                    'pjaxContainer' => '#grid-entrada-pjax',
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
                'id' => 'grid-entrada-pjax',
                'timeout' => 5000,
            ]);

            echo
            GridView::widget([
                'dataProvider' => $dataProviderEntrada,
                //'filterModel' => $searchModelEntrada,
                'columns' => [
                    [
                        'attribute' => 'is_ativo',
                        'format' => 'boolean',
                        'contentOptions' => ['style' => 'text-align:right;'],
                    ],
                    [
                        'attribute' => 'nr_lote',
                        'contentOptions' => ['style' => 'text-align:right;'],
                    ],
                    [
                        'attribute' => 'quantidade',
                        'value' => function($model) {
                            return Yii::$app->formatter->asDecimal($model->quantidade, 3);
                        },
                        'contentOptions' => ['style' => 'text-align:right;'],
                        'filter' => false
                    ],
//                
                    [
                        'attribute' => 'quantidade_vendida',
                        'value' => function($model) {
                            return Yii::$app->formatter->asDecimal($model->quantidade_vendida, 3);
                        },
                        'contentOptions' => ['style' => 'text-align:right;'],
                        'filter' => false
                    ],
                    [
                        'attribute' => 'custo_fabricacao',
                        'value' => function($model) {
                            return Yii::$app->formatter->asDecimal($model->custo_fabricacao, 3);
                        },
                        'contentOptions' => ['style' => 'text-align:right;'],
                        'filter' => false
                    ],
                    [
                        'attribute' => 'dt_fabricacao',
                        'format' => 'date',
                        'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
                    ],
                    [
                        'attribute' => 'dt_vencimento',
                        'format' => 'date',
                        'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Ações',
                        'headerOptions' => ['style' => 'color:#337ab7'],
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update-entrada', 'pk_entrada' => $model->pk_entrada], [
                                            'class' => 'update-entrada',
                                            'title' => 'Atualizar',
                                ]);
                            },
//                         
                            'delete' => function($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-entrada', 'pk_entrada' => $model->pk_entrada], [
                                            'class' => '',
                                            'data' => [
                                                'confirm' => 'Tem certeza que deseja remover esta entrada?',
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
