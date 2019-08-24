<?php

use app\models\Preco;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use kartik\number\NumberControl;

/* @var $this View */
/* @var $model Preco */
/* @var $form ActiveForm */
?>
<script>
    $(document).ready(function () {
        $('#sugestao').click(function () {
            $('#preco-codigo_barras').val($('#sugestao').attr('value'));
        });
    });
</script>
<div class="preco-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'fk_produto')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'denominacao')->textInput(['maxlength' => true, 'autofocus' => '']) ?>



    <div class="container-fluid">
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
            <div class="col-sm-4" style="background-color:lavende rblush;"> 
                <?= $form->field($model, 'codigo_barras')->textInput(['maxlength' => true]) ?>
                <button type="button" id="sugestao" value='<?= Preco::getSugestaoCodigoBarras() ?>'>   Usar código de barras sugerido:&nbsp; <?= Preco::getSugestaoCodigoBarras() ?></button>
            </div>
            <div class="col-sm-4" >  
           
              
                <?php
               
                echo $form->field($model, 'quantidade')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'prefix' => ' ',
                        'suffix' => '  ' . (!empty($model->produto->unidadeMedida)?$model->produto->unidadeMedida->unidade_medida:'Sem Unidade de Medida'),
                        'allowMinus' => false
                    ],]);
                ?>
            </div>

        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
