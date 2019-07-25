<?php

use app\models\Preco;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model Preco */
/* @var $form ActiveForm */
?>

<div class="preco-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'fk_produto')->hiddenInput() ?>

    <?= $form->field($model, 'denominacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->textInput(['maxlength' => true]) ?>
     <?= $form->field($model, 'codigo_barras')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantidade', [
    'addon' => [ 
        //'prepend' => ['content' => '$', 'options'=>['class'=>'alert-success']],
        'append' => ['content' =>  'em '.$model->produto->unidade_medida, 'options'=>['style' => 'font-family: Monaco, Consolas, monospace;']],
    ]
]);?>

       

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
