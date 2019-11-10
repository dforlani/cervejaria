<?php

use app\models\Preco;
use kartik\number\NumberControl;
use kartik\widgets\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>

<script>

    $(document).ready(function () {
        $('#itemvenda-quantidade-disp').change(function () {
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
                        $('#btn_incluir').focus();
                        calculaValorFinal();
                    });
        } else {
            $('#itemvenda-preco_final-disp').val('');
            $('#itemvenda-preco_unitario-disp').val('');
            $('#estoque_atual').text('')

        }
    }
</script>




<div class='panel panel-primary' style="background-color:lavender; ">
    <!--Itens-->

    <div class="panel-heading">
        <h3 class="panel-title">Itens</h3>
    </div>
    <div class="panel-body" style="padding: 5px">


        <div class="item-venda-form">


            <?php $form = ActiveForm::begin(['action' => ['adiciona-item-form', 'id' => $model->pk_venda]]); ?>  
            <div class="container-fluid">
                <div class="row">
                    <div class="col" style="">  

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

                    <div class="col-sm-3" style="">  

                        <?=
                        $form->field($modelItem, 'quantidade')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false,
                                'digits' => 3,
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-3" style=""> 
                        <?=
                        $form->field($modelItem, 'preco_unitario')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false,
                            ],
                            'displayOptions' => ['readonly' => true, 'tabindex' => "-1"]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-3" style="">  

                        <?=
                        $form->field($modelItem, 'preco_final')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false,
                            ],
                            'displayOptions' => ['readonly' => true, 'tabindex' => "-1"]
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-2" style="">  
                        <br>
                        <?= Html::submitButton('Incluir', ['id' => 'btn_incluir', 'class' => 'btn btn btn-primary']) ?>
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
            'options' => ['style' => 'font-size:12px;'],
            'columns' => [
                [
                    'label' => 'Produto',
                    'value' => 'preco.nomeProdutoPlusDenominacaoSemBarras',
                    'contentOptions' => ['style' => 'font-size:12px;'],
                ],
                [
                    'attribute' => 'quantidade',
                    'value' => function($model) {
                        return Yii::$app->formatter->asDecimal($model->quantidade, 3);
                    },
                    'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
                ],
                [
                    'attribute' => 'preco_unitario',
                    'format' => 'currency',
                    'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
                ],
                [
                    'attribute' => 'preco_final',
                    'format' => 'currency',
                    'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
                ],
                [
                    'attribute' => 'dt_inclusao',
                    'format' => 'datetime',
                    'label' => 'Hora',
                    'contentOptions' => ['style' => 'text-align:right;font-size:12px;'],
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

<?php
$this->registerJs(
        //abre e fecha a tela para que ela fique com o foco
        "$(document).ready(function(){
            
         //$('#itemvenda-fk_preco').select2('open');         
         $('#itemvenda-fk_preco').select2('focus');
    });", View::POS_READY, 'my-buttdon-handler'
);
?>