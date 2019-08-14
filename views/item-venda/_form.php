<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemVenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-venda-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fk_venda')->textInput() ?>

    <?= $form->field($model, 'fk_preco')->textInput() ?>

    <?= $form->field($model, 'quantidade')->textInput() ?>

    <?= $form->field($model, 'preco_unitario')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco_final')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
