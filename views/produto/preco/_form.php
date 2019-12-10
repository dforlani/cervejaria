<?php

use app\models\Preco;
use kartik\number\NumberControl;
use kartik\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Preco */
/* @var $form ActiveForm */
?>
<script>

    var custo_compra_producao = <?= !empty($model->produto->custo_compra_producao) ? $model->produto->custo_compra_producao : 0 ?>;


    $(document).ready(function () {
        $('#sugestao').click(function () {
            $('#preco-codigo_barras').val($('#sugestao').attr('value'));
        });

        $('#preco-quantidade').change(function () {
            calculaCusto();
        });


        $("input[name='Preco[is_tap_list]']").click(function (evt) {
            var valor = $("input[name='Preco[is_tap_list]']:checked").val();
            showPosTapList(valor);
        });
    });



    function showPosTapList(valor) {
        if (valor == 0) {
            $('#divPosTapList').hide();
        } else {
            $('#divPosTapList').fadeIn();
        }
    }

    function calculaCusto() {
        if ((Number($('#preco-quantidade').val()))) {
            $('#hlp_preco_custo').text(($('#preco-quantidade').val() * custo_compra_producao).toLocaleString("pt-BR", {style: "currency", currency: "BRL"}));
        } else {
            $('#hlp_preco_custo').text('');
        }
    }

    showPosTapList(<?= !empty($model->is_tap_list) ? $model->is_tap_list : 0 ?>);



</script>
<div class="preco-form">

    <?php $form = ActiveForm::begin(); ?>



    <div class="container-fluid">
        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'fk_produto')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'denominacao')->textInput(['maxlength' => true, 'autofocus' => '']) ?>
        <div class="row">
            <div class="col-sm-4" >  
                <?php
                echo $form->field($model, 'preco')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'prefix' => 'R$ ',
                        'suffix' => '',
                        'allowMinus' => false
                    ],
                ]);
                ?>
            </div>
            <div class="col-sm-4" style=";"> 
                <?= $form->field($model, 'codigo_barras')->textInput(['maxlength' => true]) ?>
                <button type="button" id="sugestao" value='<?= Preco::getSugestaoCodigoBarras() ?>'>   Usar código de barras sugerido:&nbsp; <?= Preco::getSugestaoCodigoBarras() ?></button>
            </div>
            <div class="col-sm-4" >  


                <?php
                echo $form->field($model, 'quantidade')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'prefix' => ' ',
                        'suffix' => '  ' . (!empty($model->produto->unidadeMedida) ? $model->produto->unidadeMedida->unidade_medida : 'Sem Unidade de Medida'),
                        'allowMinus' => false,
                        'digits' => 3,
                    ],]);
                ?>
                <span style="color:blue;">Preço de Custo: <b><span style="color:blue;font-weight:bold" id='hlp_preco_custo'> <?= $model->produto->custo_compra_producao * $model->quantidade ?> </span> </b>            
            </div>
        </div>
        <br>
        <div class="row">
            <div class='panel panel-info' style="background-color:#fff2e6; ">                               

                <div class="panel-heading">
                    <h3 class="panel-title"><b>Promoção<b></h3>
                </div>
                <div class="panel-body" style="">
                    <div class="col-sm-4" >  
                        <?php
                        echo $form->field($model, 'promocao_quantidade_atingir')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-4" style=";"> 
                        <?php
                        echo $form->field($model, 'promocao_desconto_aplicar')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => 'R$ ',
                                'suffix' => '',
                                'allowMinus' => false,
                                 'allowMinus' => true
                            ],
                        ]);
                        ?>
                    </div>
                    <div class="col-sm-4" style="height: 32px">
                        <?php
                        echo $form->field($model, 'is_promocao_ativa')->widget(SwitchInput::classname(), []);
                        ?>
                    </div>
                </div>
                <span style="color:blue;">A cada quantidade de itens comprados, o sistema irá adicionar um item com o valor do desconto. </span> </b>            
            </div>
        </div>


        <div class="form-group">
            <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
