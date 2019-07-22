<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Preco */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="preco-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pk_preco')->textInput() ?>

    <?= $form->field($model, 'fk_produto')->textInput() ?>

    <?= $form->field($model, 'denominacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
