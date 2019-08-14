<script>
    //$("form input:text, form textarea").first().focus();
    </script>
<?php

use app\models\Cliente;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Cliente */
/* @var $form ActiveForm */
?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['autofocus' => '', 'maxlength' => true]) ?>

    <?= $form->field($model, 'telefone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cpf')->textInput(['maxlength' => true]) ?>
    <?php
//    $form->field($model, 'dt_nascimento')->widget(\yii\jui\DatePicker::class, [
//        'dateFormat' => 'dd/MM/yyyy'
//    ]);
    echo $form->field($model, 'dt_nascimento')->widget(DateControl::classname(), [
    'type' => 'date',
    'ajaxConversion' => true,
    'autoWidget' => true,
    'widgetClass' => '',
    'displayFormat' => 'php:d/m/Y',
    'saveFormat' => 'php:Y-m-d',
    'saveTimezone' => 'UTC',
    'widgetOptions' => [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'php:d/m/Y',
        ],
         'language' => 'pt-BR'
    ]
]);
    ?>


    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
