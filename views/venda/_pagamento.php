<?php

use app\models\Configuracao;
use app\models\Preco;
use kartik\editable\Editable;
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
        $('#itemvenda-fk_preco').select2('close');
        var valor_total = <?= $model->valor_total ?>;
        var valor_final = <?= $model->valor_final ?>;
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
        $('#venda-desconto-disp').change(function () {
            if ($('#venda-desconto').val() != '') {
                valor_final = valor_total - parseFloat($('#venda-desconto').val());
                atualizaTrocoEFaltando();
                $('#venda-valor_final').text(valor_final.toLocaleString("pt-BR", {minimumFractionDigits: 2}));
            } else {
                valor_final = valor_total;
                atualizaTrocoEFaltando();
                $('#venda-valor_final').text(valor_total);
            }
        });
        
        $('#venda-valor_pago_dinheiro-disp').change(function () {
            console.log('descontro');

            atualizaTrocoEFaltando();
        });
        $('#venda-valor_pago_debito-disp').change(function () {

            atualizaTrocoEFaltando();
        });
        $('#venda-valor_pago_credito-disp').change(function () {

            atualizaTrocoEFaltando();
        });
        //escolhi deixar sem a opção de alterar quando está sendo devolvido de troco
//        $('#troco').click(function () {
//            $('#troco').hide();
//            $('#troco_input').fadeIn();
//        });
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
        /**
         * Previne que o form seja submetido ao dar enter e vai para o próximo elemento com tabindex. Já que kartik cria vários elementos escondidos
         * @param {type} event
         * @returns {Boolean}
         */
        $('input[type=text]').keydown(function (event) {

            event.stopImmediatePropagation();
            if (event.which == 13)
            {
                var tabindex = $(this).attr('tabindex');
                tabindex++; //increment tabindex
                //after increment of tabindex ,make the next element focus
                $('[tabindex=' + tabindex + ']').focus();
                return false; // to cancel out Onenter page postback in asp.net
            }
        });


        function getSaldo() {
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
            console.log(saldo);
            return saldo;
        }

        function atualizaTrocoEFaltando() {
            atualizaTroco(getSaldo());
            atualizaFaltando(getSaldo());
        }
        function atualizaTroco(saldo) {
            if (saldo > 0) {                
                $('#troco').text((saldo).toLocaleString("pt-BR", {minimumFractionDigits: 2}));                
                $('#venda-troco-disp').val((saldo).toLocaleString("pt-BR", {minimumFractionDigits: 2}));
                $('#venda-troco').val((saldo));
            } else {
                $('#troco').text((0).toLocaleString("pt-BR", {minimumFractionDigits: 2}));
                $('#venda-troco-disp').val((0).toLocaleString("pt-BR", {minimumFractionDigits: 2}));
                $('#venda-troco').val(0);
            }
        }

        function atualizaFaltando(saldo) {
            if (saldo >= 0) {
                $('#faltando').text((0).toLocaleString("pt-BR", {minimumFractionDigits: 2}));
            } else {
                $('#faltando').text((-1 * saldo).toLocaleString("pt-BR", {minimumFractionDigits: 2}));
            }
        }

        
        atualizaFaltando(getSaldo());
        
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
                            'displayOptions' => ['autofocus' => '', 'tabindex' => 1]
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
                            'displayOptions' => ['autofocus' => '', 'tabindex' => 2]
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
                            'displayOptions' => ['autofocus' => '', 'tabindex' => 3]
                        ]);
                        ?>
                    </div>
                </div>
                <div class="row">

                    <h1 id='troco_div' style="color: red">
                        Faltando:   <span id='faltando'></span>

                    </h1>

                </div>
                <div class="row">

                    <h1 id='troco_div' style="color: green">
                       

                        
                                Troco: <span id='troco'> <?= Yii::$app->formatter->asCurrency($model->troco) ?></span>
                        
                               
                                <span id="troco_input" style="display:none ">
                                    <?php
                                    echo $form->field($model, 'troco')->widget(NumberControl::classname(), [
                                        'maskedInputOptions' => [
                                            'prefix' => '',
                                            'suffix' => '',
                                            'allowMinus' => true
                                        ],
                                        'displayOptions' => ['autofocus' => '']
                                    ])->label(false);
                                    ?>
                                </span>
                            

                


                    </h1>

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
                            'displayOptions' => ['autofocus' => '', 'tabindex' => 4]
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

        <?= Html::submitButton('Fec<u>h</u>ar Venda', ['value' => 'paga', 'accesskey' => "h", 'id' => 'fechar_venda', 'class' => 'btn btn-primary', 'name' => 'Venda[estado]', 'tabindex' => 5]) ?>
        <?= Html::submitButton('Salvar P<u>r</u>é-Pagamento', ['accesskey' => "r", 'id' => 'bt_salvar_pre_pagamento', 'class' => 'btn btn-success', 'tabindex' => 6]) ?>        
        <?php
        $mostrar = Configuracao::getConfiguracaoByTipo("is_mostrar_botao_fiado");

        if (!empty($mostrar) && ($mostrar->valor == "1")) {
            echo Html::submitButton(('Fiado'), ['value' => 'fiado', 'class' => 'btn btn-danger', 'id' => 'bt_fiado', 'name' => 'Venda[estado]', 'tabindex' => 7]);
        }
        ?>


    </div>

    <?php ActiveForm::end(); ?>

</div>
