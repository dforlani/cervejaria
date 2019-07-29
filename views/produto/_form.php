<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Produto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="produto-form">

    <?php $form = ActiveForm::begin(); ?>

    

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'autofocus' => '']) ?>

    <?= $form->field($model, 'estoque')->textInput() ?>

    <?php
                        echo $form->field($modelItem, 'fk_unidade_medida')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(UnidadeMedida::find()->all, 'pk_unidade_medida', 'unidade_medida'),
                            'options' => ['placeholder' => 'Selecione uma Unidade de Medida'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
//                            'pluginEvents' => [
//                                "change" => "function(e) { changeProduto(); }  ",
//                            ]
                        ])->label('Unidade de Medida');
                        ?>
  

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
