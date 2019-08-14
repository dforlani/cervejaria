<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VendaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="venda-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pk_venda') ?>

    <?= $form->field($model, 'fk_cliente') ?>

    <?= $form->field($model, 'fk_comanda') ?>

    <?= $form->field($model, 'fk_usuario_iniciou_venda') ?>

    <?= $form->field($model, 'fk_usuario_recebeu_pagamento') ?>

    <?php // echo $form->field($model, 'valor_total') ?>

    <?php // echo $form->field($model, 'desconto') ?>

    <?php // echo $form->field($model, 'valor_final') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <?php // echo $form->field($model, 'dt_venda') ?>

    <?php // echo $form->field($model, 'dt_pagamento') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
