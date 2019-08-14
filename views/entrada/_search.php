<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntradaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entrada-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pk_entrada') ?>

    <?= $form->field($model, 'fk_usuario') ?>

    <?= $form->field($model, 'fk_produto') ?>

    <?= $form->field($model, 'quantidade') ?>

    <?= $form->field($model, 'dt_entrada') ?>

    <?php // echo $form->field($model, 'custo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
