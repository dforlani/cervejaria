<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnidadeMedida */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unidade-medida-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'unidade_medida')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
