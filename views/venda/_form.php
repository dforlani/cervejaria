<?php

use app\models\Venda;
use kartik\editable\Editable;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Venda */
/* @var $form ActiveForm */
?>

<div class="venda-form">






    <div class="container-fluid">
        <div class="row" style='font-size: 25px'>
            <div class="co  l-sm-2" style="background-color:laven der;">  
                <label>Valor Total:</label>
                <span><?php echo Yii::$app->formatter->asCurrency($model->valor_total) ?></span>
                <?php // $form->field($model, '<label>Desconto</label><br>')->textInput(['maxlength' => true, 'style' => 'text-align:right', 'readonly' => true,]) ?>

            </div>
            <div class="co l-sm-2" style="background-color:lavende rblush;"> 
                <label>Desconto:</label>
                <?php
                //$form->field($model, 'desconto')->textInput(['maxlength' => true, 'style' => 'text-align:right',]); 
                echo Editable::widget([
                    'attribute' => 'desconto',
                    'model' => $model,
                    //'name' => 'desconto',
                    //'id' => 'desconto',
                    'format' => 'currency',
                    'asPopover' => true,
                    //'header' => 'Name',
                    'type' => 'GET',
                    'size' => 'md',
                    // 'options' => ['class' => 'form-control', 'placeholder' => 'Enter person name...'],
                    'formOptions' => ['action' => ['altera-desconto', 'id' => $model->pk_venda]],
                    'pluginEvents' => [
                        "editableSuccess" => "function(event, val, form, data) {"
                        . "$('#valor_final').text(data.valor_final); "
                        . "}",
                    ]
                ]);
                ?>
            </div>
            <div class="co l-sm-2" style="background-color:lav ender;">  
                <?php // $form->field($model, 'valor_final')->textInput(['maxlength' => true, 'readonly' => true, 'style' => 'text-align:right',])   ?>
                <label>Valor Final:</label>
                <span><b><div id='valor_final'><?php echo Yii::$app->formatter->asCurrency($model->valor_final) ?>
                        </div></b>
                </span>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="co l-sm" style="background-color:lav ender;">  
                <br>
                <?= Html::submitButton(('Pagar'), ['class' => 'btn btn-primary', 'value' => 'pagar', 'name' => 'Venda[button]']) ?> &nbsp;
                <?= Html::submitButton(('Fiado'), ['class' => 'btn btn-success', 'value' => 'fiado', 'name' => 'Venda[button]']) ?>&nbsp;
                <?= Html::buttonInput(('Comprovante'), ['id' => 'bt_comprovante', 'onClick' => "window.open('comprovante?id={$model->pk_venda}', '_blank');", 'class' => 'btn btn-warning']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
