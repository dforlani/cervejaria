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

<div class="venda-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    
    echo $form->field($model, 'fk_cliente')->widget(Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Cliente::find()->all(), 'pk_cliente', 'nome'),
        'options' => ['placeholder' => 'Selecione um cliente'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Cliente');
    ?>
    
     <?php
    
    echo $form->field($model, 'fk_comanda')->widget(Select2::classname(), [
        'data' => \yii\helpers\ArrayHelper::map(\app\models\Comanda::find()->all(), 'pk_comanda', 'numero'),
        'options' => ['placeholder' => 'Selecione um cliente'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Comanda');
    ?>

    <?= $form->field($model, 'valor_total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desconto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor_final')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
