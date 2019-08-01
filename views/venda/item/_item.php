<script>

    $(document).ready(function () {
        $('#itemvenda-quantidade').change(function () {
            calculaValorFinal();
        });

    });

    function calculaValorFinal() {
        if (($('itemvenda-quantidade').val() != '') && ($('#itemvenda-preco_unitario').val() != '')) {
            $('#itemvenda-preco_final').val(parseFloat($('#itemvenda-quantidade').val()) * parseFloat($('#itemvenda-preco_unitario').val()));
        } else {
            $('#itemvenda-preco_final').val('');
        }
    }
    /**
     * Quando muda o produto selecionado, este atualiza o preço unitário dele e calcula sozinho o valo final
     * @returns {undefined}
     */
    function changeProduto() {
        if ($('#itemvenda-fk_preco').val() != '') {
            $.ajax({
                method: "GET",
                url: "busca-produto",
                dataType: 'json',
                data: {id: $('#itemvenda-fk_preco').val()}
            })
                    .done(function (msg) {
                        $('#itemvenda-preco_unitario').val(msg);
                        calculaValorFinal();
                    });
        } else {
            $('#itemvenda-preco_final').val('');
            $('#itemvenda-preco_unitario').val('')

        }
    }
</script>


<?php

use app\models\Preco;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;



?>

<div class='panel panel-primary' style="background-color:lavender; ">
    <!--Itens-->

    <div class="panel-heading">
        <h3 class="panel-title">Itens</h3>
    </div>
    <div class="panel-body" style="padding: 5px">


        <div class="item-venda-form">


            <?php $form = ActiveForm::begin(); ?>  
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4" style="background-color:lavend er;">  
                        <?php
                        echo $form->field($modelItem, 'fk_preco')->widget(Select2::classname(), [
                            'data' => Preco::getArrayProdutosPrecos(),
                            'options' => ['placeholder' => 'Selecione um Produto'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'pluginEvents' => [
                                "change" => "function(e) { changeProduto(); }  ",
                            ]
                        ])->label('Produto');
                        ?>

                        <?= $form->field($modelItem, 'fk_venda')->hiddenInput()->label(false) ?>

                    </div>
                    <div class="col-sm-2" style="background-color:lavend er;">  
                        <?= $form->field($modelItem, 'quantidade')->textInput() ?>
                    </div>
                    <div class="col-sm-2" style="background-color:lav enderblush;"> 
                        <?= $form->field($modelItem, 'preco_unitario')->textInput(['maxlength' => true, 'readonly' => true,]) ?>
                    </div>
                    <div class="col-sm-2" style="background-color:lave nder;">  
                        <?= $form->field($modelItem, 'preco_final')->textInput(['maxlength' => true, 'readonly' => true,]) ?>
                    </div>
                    <div class="col-sm-2" style="background-color:lave nder;">  
                        <br>
                        <?= Html::submitButton('Incluir', ['class' => 'btn btn btn-primary']) ?>
                    </div>
                </div>
            </div>



            <span class="border border-primary"></span>



            <?php ActiveForm::end(); ?>

        </div>


        <?=
        GridView::widget([
            'dataProvider' => $dataProviderItem,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Produto',
                    'value' => 'preco.nomeProdutoPlusDenominacao',
                ],
          
                [
                    'attribute' => 'quantidade',
                    'format' => 'currency',
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'attribute' => 'preco_unitario',
                    'format' => 'currency',
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'attribute' => 'preco_final',
                    'format' => 'currency',
                    'contentOptions' => ['style' => 'text-align:right'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => 'Ações',
                    'headerOptions' => ['style' => 'color:#337ab7'],
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete-item', 'id' => $model->pk_item_venda], [
                                        'class' => '',
                                        'data' => [
                                            'confirm' => 'Tem certeza que deseja remover este item?',
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
</div>