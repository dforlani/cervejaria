<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrecoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="preco-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pk_preco') ?>

    <?= $form->field($model, 'fk_produto') ?>

    <?= $form->field($model, 'denominacao') ?>

    <?= $form->field($model, 'preco') ?>

    <?= $form->field($model, 'quantidade') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
