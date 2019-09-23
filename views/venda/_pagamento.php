<?php

use app\models\Preco;
use kartik\number\NumberControl;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Preco */
/* @var $form ActiveForm */
?>
<script>



    $(document).ready(function () {
        var valor_total = <?= $model->valor_total ?>;
        var valor_final = <?= $model->valor_final ?>;
        console.log(valor_final);
        //ao entrar no campo, remove o 0,00
        $("input[type='text']").click(function () {
            if ($(this).val() == '0,00')
                $(this).val('');
            $(this).select();
        });
        //ao entrar no campo, remove o 0,00
        $("input[type='text']").focus(function () {
            if ($(this).val() == '0,00')
                $(this).val('');
            $(this).select();
        });
        //ao sair  do campo, devolve o 0,00
        $("input[type='text']").blur(function () {
            if ($(this).val() == '')
                $(this).val('0,00');
        });
        /**
         * Vai atualizar o valor final com base no desconto aplicado
         * @returns {undefined}
         */
        $('#venda-desconto').change(function () {

            if ($('#venda-desconto').val() != '') {
                valor_final = valor_total - parseFloat($('#venda-desconto').val());
                atualizaTroco();
                $('#venda-valor_final').text(valor_final.toLocaleString("pt-BR", {minimumFractionDigits: 2}));
            } else {
                valor_final = valor_total;
                atualizaTroco();
                $('#venda-valor_final').text(valor_total);
            }
        });
        $('#venda-valor_pago_dinheiro').change(function () {

            atualizaTroco();
        });
        $('#venda-valor_pago_debito').change(function () {

            atualizaTroco();
        });
        $('#venda-valor_pago_credito').change(function () {

            atualizaTroco();
        });

        function atualizaTroco() {
            dinheiro = 0;
            credito = 0;
            debito = 0;
            if ($('#venda-valor_pago_dinheiro').val() != '') {
                dinheiro = parseFloat($('#venda-valor_pago_dinheiro').val());
            }

            if ($('#venda-valor_pago_credito').val() != '') {
                credito = parseFloat($('#venda-valor_pago_credito').val());
            }

            if ($('#venda-valor_pago_debito').val() != '') {
                debito = parseFloat($('#venda-valor_pago_debito').val());
            }

            saldo = dinheiro + credito + debito - valor_final;
            if (saldo > 0) {
                $('#troco').text("Troco: " + (saldo).toLocaleString("pt-BR", {minimumFractionDigits: 2}));
            } else {
                $('#troco').text("Faltando: " + (-1 * saldo).toLocaleString("pt-BR", {minimumFractionDigits: 2}));
            }
        }

        /**
         * Pede confirmação de venda com valor pago menor que o valor final
         * @returns {undefined}
         */
        $('#fechar_venda').click(function (evt) {
            dinheiro = 0;
            credito = 0;
            debito = 0;
            if ($('#venda-valor_pago_dinheiro').val() != '') {
                dinheiro = parseFloat($('#venda-valor_pago_dinheiro').val());
            }

            if ($('#venda-valor_pago_credito').val() != '') {
                credito = parseFloat($('#venda-valor_pago_credito').val());
            }

            if ($('#venda-valor_pago_debito').val() != '') {
                debito = parseFloat($('#venda-valor_pago_debito').val());
            }

            if ((dinheiro + debito + credito) < valor_final) {
                if (!confirm('Tem certeza que deseja fechar a venda com valor de pagamento menor que o valor final?')) {
                    evt.preventDefault();
                }

            }
        });
        
           //inicia com o campo de valor pago selecionado
        $('#venda-valor_pago_dinheiro-disp').click();    


    }
    );




    $('#fechar_venda').click(function () {
        $('#estado').attr('value', 'paga');
    });

    $('#bt_fiado').click(function () {
        $('#estado').attr('value', 'fiado');
    });

    $('#bt_salvar_pre_pagamento').click(function () {
        $('#estado').attr('value', 'aberta');
    });



</script>
<div class="preco-form">

    <?php $form = ActiveForm::begin(['id' => 'teste']); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'pk_venda')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'estado')->hiddenInput(['id' => 'estado'])->label(false) ?>

    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-5">
                <div class="row">
                      <div class="col-sm-6" style="text-align: right"> 
                     </div>
                    <div class="col-sm-6" >  
                        <?php
                        echo $form->field($model, 'valor_pago_dinheiro')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false
                            ],
                            'displayOptions' => ['autofocus' => '',]
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                      <div class="col-sm-6" style="text-align: right"> 
                     </div>
                    <div class="col-sm-6" style=";"> 
                        <?php
                        echo $form->field($model, 'valor_pago_debito')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false
                            ],
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">
                      <div class="col-sm-6" style="text-align: right"> 
                     </div>
                    <div class="col-sm-6" > 
                        <?php
                        echo $form->field($model, 'valor_pago_credito')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false,
                            ],
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">

                    <h1 style="color: red"> <span id='troco'><?= $model->getTroco() ?></span></h1>

                </div>
            </div>

            <div class="col-sm-5">
                <div class="row">
                    <h2> Valor Total: <span id='valor_final'><?= Yii::$app->formatter->asCurrency($model->valor_total) ?></span></h2>
                </div>
                <div class="row">
                     <div class="col-sm-6" style="text-align: right"> 
                     </div>
                    <div class="col-sm-6" style="text-align: right"> 
                        <?php
                        echo $form->field($model, 'desconto')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false
                            ],
                        ]);
                        ?>
                    </div>

                </div>

                <div class="row">
                    <br><br>
                    <br>
                    <br>
                    <h1 style='color:blue'> Valor Final: <span id='venda-valor_final'><?= Yii::$app->formatter->asCurrency($model->valor_final) ?></span></h1>
                </div>
            </div>


        </div>
    </div>



    <div class="form-group">


        <?= Html::submitButton(('Fiado'), ['value' => 'fiado', 'class' => 'btn btn-danger', 'id' => 'bt_fiado', 'name' => 'Venda[estado]']) ?>
        <?= Html::submitButton('Salvar P<u>r</u>é-Pagamento', ['accesskey' => "r", 'id' => 'bt_salvar_pre_pagamento', 'class' => 'btn btn-success']) ?>        
        <?= Html::submitButton('Fec<u>h</u>ar Venda', ['value' => 'paga', 'accesskey' => "h", 'id' => 'fechar_venda', 'class' => 'btn btn-primary', 'name' => 'Venda[estado]']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
