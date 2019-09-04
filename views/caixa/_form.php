<?php

use app\models\Caixa;
use kartik\number\NumberControl;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Caixa */
/* @var $form ActiveForm */
?>

<div class="caixa-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php
    echo $form->field($model, 'valor')->widget(NumberControl::classname(), [
        'maskedInputOptions' => [
            'prefix' => 'R$ ',
            'suffix' => '',
            'allowMinus' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'tipo')->radioList(['Abertura de Caixa' => 'Abertura de Caixa', 'Sangria' => 'Sangria'], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
