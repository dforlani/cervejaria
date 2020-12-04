<?php

use kartik\number\NumberControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
?>


<div class="form-group">


    <div class="row">

        <div class="col-sm-3">
            <?= $form->errorSummary($model) ?>            

            <?php
            echo $form->field($model, 'valor_dinheiro')->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'prefix' => 'R$ ',
                    'suffix' => '',
                    'allowMinus' => false
                ],
            ])->label('Valor Abertura de Caixa');
            ?>

        </div>
        <div class="col-1">
            <br>
            <?= Html::submitButton('Abrir Caixa', ['class' => 'btn btn-success', 'name' => 'abrir', 'value' => 'abrir']) ?>        
        </div>


    </div>

    <?php ActiveForm::end(); ?>
</div>

