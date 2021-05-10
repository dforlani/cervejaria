<?php

use app\models\Entrada;
use kartik\datecontrol\DateControl;
use kartik\number\NumberControl;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Entrada */
/* @var $form ActiveForm */
?>

<div class="entrada-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <?=
    $form->field($model, 'is_ativo')->widget(SwitchInput::classname(), ['pluginOptions' => [
            'onText' => 'Sim',
            'offText' => 'Não',
    ]]);
    ?>
    <span style="color: red;font-size: 13px"> <b>(Ao menos um entrada precisa estar ativa, para que o produto possa ser vendido)</b></span>
    <br>
    <br>
    <?= $form->field($model, 'nr_lote')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'quantidade')->widget(NumberControl::classname(), [
        'maskedInputOptions' => [
            'prefix' => ' ',
            'suffix' => '',
            'allowMinus' => false,
            'digits' => 3,
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'custo_fabricacao')->widget(NumberControl::classname(), [
        'maskedInputOptions' => [
            'prefix' => ' R$ ',
            'suffix' => '',
            'allowMinus' => false,
            'digits' => 2,
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'dt_fabricacao')->widget(DateControl::classname(), [
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

    <?=
    $form->field($model, 'dt_vencimento')->widget(DateControl::classname(), [
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
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>
    <?= $form->field($model, 'pk_entrada')->hiddenInput()->label('') ?>

    <?= $form->field($model, 'fk_usuario')->hiddenInput(['maxlength' => true])->label('') ?>

    <?= $form->field($model, 'fk_produto')->hiddenInput()->label('') ?>
    <?php ActiveForm::end(); ?>

</div>
