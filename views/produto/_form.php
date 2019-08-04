<?php

use app\models\Produto;
use app\models\UnidadeMedida;
use kartik\datecontrol\DateControl;
use kartik\number\NumberControl;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Produto */
/* @var $form ActiveForm */
?>

<div class="produto-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="container-fluid">
        <?= $form->field($model, 'is_vendavel')->checkbox([1 => 'Sim', 0 => 'Não']) ?>
        <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'autofocus' => '']) ?>

        <div class="row">
            <div class="col-sm-3" style="background-color:laven der;"> 
                <?=
                $form->field($model, 'estoque_inicial')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'prefix' => ' ',
                        'suffix' => '',
                        'allowMinus' => false
                    ],
                ]);
                ?>

            </div>
            <div class="col-sm-3" style="background-color:lavende rblush;"> 
                 <?=
                $form->field($model, 'estoque')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'prefix' => ' ',
                        'suffix' => '',
                        'allowMinus' => false
                    ],
                ]);
                ?>
              
            </div>
            <div class="col-sm-3" style="background-color:lav ender;">  
                 <?=
                $form->field($model, 'estoque_minimo')->widget(NumberControl::classname(), [
                    'maskedInputOptions' => [
                        'prefix' => ' ',
                        'suffix' => '',
                        'allowMinus' => false
                    ],
                ]);
                ?>
              
            </div>
            <div class="col-sm-3" style="background-color:lav ender;">  
                <?php
                echo $form->field($model, 'fk_unidade_medida')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(UnidadeMedida::find()->all(), 'pk_unidade_medida', 'unidade_medida'),
                    'options' => ['placeholder' => 'Selecione uma Unidade de Medida'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
//                            'pluginEvents' => [
//                                "change" => "function(e) { changeProduto(); }  ",
//                            ]
                ])->label('Unidade de Medida');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6" style="background-color:laven der;">
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
            </div>
            <div class="col-sm-6" style="background-color:laven der;">  
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
            </div>

        </div>
        <?= $form->field($model, 'nr_lote')->textInput() ?>
    </div>






    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
