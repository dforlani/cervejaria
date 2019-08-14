<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Entrada */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entrada-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pk_entrada')->textInput() ?>

    <?= $form->field($model, 'fk_usuario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fk_produto')->textInput() ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <?= $form->field($model, 'dt_entrada')->textInput() ?>

    <?= $form->field($model, 'custo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
