<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemVendaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-venda-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'fk_venda') ?>

    <?= $form->field($model, 'fk_preco') ?>

    <?= $form->field($model, 'quantidade') ?>

    <?= $form->field($model, 'preco_unitario') ?>

    <?= $form->field($model, 'preco_final') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
