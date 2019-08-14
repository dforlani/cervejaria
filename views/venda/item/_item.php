<script>

    $(document).ready(function () {
        $('#itemvenda-quantidade-disp').change(function () {
            console.log('oi');
            calculaValorFinal();
        });

    });

    function calculaValorFinal() {
        if (($('#itemvenda-quantidade').val() != '') && ($('#itemvenda-preco_unitario').val() != '')) {
            $('#itemvenda-preco_final-disp').val(parseFloat($('#itemvenda-quantidade').val()) * parseFloat($('#itemvenda-preco_unitario').val()));
            $('#itemvenda-preco_final-disp').blur();
        } else {
            $('#itemvenda-preco_final-disp').val('');

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
                        $('#itemvenda-preco_unitario-disp').val(msg.preco);
                        $('#itemvenda-preco_unitario-disp').blur()
                        $('#estoque_atual').text('Estoq Atual: ' + msg.estoque_atual);
                        calculaValorFinal();
                    });
        } else {
            $('#itemvenda-preco_final-disp').val('');
            $('#itemvenda-preco_unitario-disp').val('');
            $('#estoque_atual').text('')

        }
    }
</script>


<?php

use app\models\Preco;
use kartik\number\NumberControl;
use kartik\widgets\Select2;
use yii\grid\GridView;
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
                    <div class="col" style="background-color:lavend er;">  
                        <br>
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
                        <span id='estoque_atual' class='danger'></span>

                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-2" style="background-color:lavend er;">  
                        <br>
                        <?=
                        $form->field($modelItem, 'quantidade')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-2" style="background-color:lav enderblush;"> 
                        <?=
                        $form->field($modelItem, 'preco_unitario')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false,
                            ],
                            'displayOptions' => ['readonly' => true]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-2" style="background-color:lave nder;">  
                        <br>
                        <?=
                        $form->field($modelItem, 'preco_final')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false,
                            ],
                            'displayOptions' => ['readonly' => true]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-2" style="background-color:lave nder;">  
                        <br><br>
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
            'layout' => '{items}{pager}{summary}',
            'columns' => [
                //  ['class' => 'yii\grid\SerialColumn'],
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