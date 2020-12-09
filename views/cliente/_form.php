<script>
    //$("form input:text, form textarea").first().focus();
</script>
<?php

use app\models\Cliente;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this View */
/* @var $model Cliente */
/* @var $form ActiveForm */
?>

<div class="cliente-form">

    <?php $form = ActiveForm::begin(); ?>
  

    <?= $form->field($model, 'nome')->textInput(['autofocus' => '', 'maxlength' => true]) ?>

    <?= $form->field($model, 'telefone')->widget(MaskedInput::className(), ['mask' => ['(99)9999-9999', '(99)99999-9999']]) ?>
    <?= $form->field($model, 'cpf')->widget(MaskedInput::className(), ['mask' => ['999.999.999-99']]) ?>
    
        <?php //echo $form->field($model, 'endereco')->textInput(['maxlength' => true]) ?> 
    <?php //echo $form->field($model, 'cod_uf_aux')->listBox(yii\helpers\ArrayHelper::map(app\models\Uf::find()->all(), 'cod_uf', 'nome_uf'), ['multiple'=>false,'size'=>1, 'prompt'=>'Selecione a UF']) ?>    
    <?php //echo $form->field($model, 'cod_mun')->listBox(yii\helpers\ArrayHelper::map(app\models\Municipio::find()->all(), 'cod_mun', 'nome_mun'), ['multiple'=>false,'size'=>1, 'prompt'=>'Selecione o Município']) ?>



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
        <?= Html::submitButton('<u>S</u>alvar', ['accesskey' => 's', 'class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
