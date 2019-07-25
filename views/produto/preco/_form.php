<?php

use app\models\Preco;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Preco */
/* @var $form ActiveForm */
?>

<div class="preco-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'fk_produto')->hiddenInput() ?>

    <?= $form->field($model, 'denominacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantidade', [
    'addon' => [ 
        'prepend' => ['content' => '$', 'options'=>['class'=>'alert-success']],
        'append' => ['content' => '.00', 'options'=>['style' => 'font-family: Monaco, Consolas, monospace;']],
    ]
]);?>

        <div class="input-group">
            <?= $form->field($model, 'quantidade')->textInput() ?>
            <span class="input-group-addon" id="basic-addon2">em <?= $model->produto->unidade_medida?></span>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
