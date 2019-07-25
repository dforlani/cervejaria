<?php

use app\models\Venda;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Venda */
/* @var $form ActiveForm */
?>
<script>
    $(document).ready(function(){
        $('#venda-desconto').change(function(){
        $('#bt_comprovante').prop('disabled', true);
        $('#bt_comprovante').prop('title', 'Só poderá ser gerado o comprovante após ter salvado');
        
    });
    });
    
    </script>
<div class="venda-form">

    <?php $form = ActiveForm::begin(); ?>




    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6" style="">  
                <?php
                echo $form->field($model, 'fk_cliente')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Cliente::find()->orderBy('nome')->all(), 'pk_cliente', 'nome'),
                    'options' => ['placeholder' => 'Selecione um cliente'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Cliente');
                ?>
            </div>
            <div class="col-sm-6" style=""> 
                <?php
                echo $form->field($model, 'fk_comanda')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Comanda::find()->orderBy('numero')->all(), 'pk_comanda', 'numero'),
                    'options' => ['placeholder' => 'Selecione um cliente'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Comanda');
                ?>
            </div>

        </div>
    </div>

    <?php if (!$model->isNewRecord) { ?>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4" style="background-color:laven der;">  
                    <?= $form->field($model, 'valor_total')->textInput(['maxlength' => true, 'readonly' => true,]) ?>
                </div>
                <div class="col-sm-4" style="background-color:lavende rblush;"> 
                    <?= $form->field($model, 'desconto')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-4" style="background-color:lav ender;">  
                    <?= $form->field($model, 'valor_final')->textInput(['maxlength' => true, 'readonly' => true,]) ?>
                </div>
            </div>
        </div>



        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3" style="background-color:laven der;">  
                    <?= $form->field($model, 'estado')->radioList(['aberta' => 'Aberta', 'fiado' => 'Fiado', 'paga' => 'Paga',], ['prompt' => '']); ?>
                <?php } ?>
            </div>
            <div class="col-sm-4" style="background-color:lavende rblush;"> 


                <div class="form-group">
                    <br>
                    <?= Html::submitButton(($model->isNewRecord ? 'Iniciar Venda' : 'Salvar'), ['class' => 'btn btn-success']) ?>

                    <?php if (!$model->isNewRecord) { ?>

                        <?= Html::buttonInput(('Comprovante'), ['id'=>'bt_comprovante', 'onClick' => "window.open('comprovante?id={$model->pk_venda}', '_blank');", 'class' => 'btn btn-warning']) ?>
                        <?= Html::buttonInput(('Fechar'), ['onClick' => "window.location='./venda'", 'class' => 'btn btn-danger']) ?>
                    <?php } ?>
                </div>
            </div>

        </div>
        <?php ActiveForm::end(); ?>

    </div>
