
<script>
    $(document).ready(function(){
        $('.kv-editable-link').focus(function(){
           $('#venda-desconto-disp').attr('autofocus','autofocus');
        });
    });
    </script>
    
<?php

use app\models\Venda;
use kartik\editable\Editable;
use kartik\number\NumberControl;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>

<div class="venda-form">






    <div class="container-fluid">
        <div class="row" >
            <div class="co  l-sm-2" style="background-color:laven der;font-size: 20px">  
                <label>Valor Total:</label>
                <span><?php echo Yii::$app->formatter->asCurrency($model->valor_total) ?></span>


            </div>
            <div class="co l-sm-2" style="background-color:lavende rblush;font-size: 20px"> 
                <label>Desconto:</label>
                <?php
                $editable = Editable::begin([
                            'inputType' => Editable::INPUT_HIDDEN,
                            'model' => $model,
                            'value' => Yii::$app->formatter->asCurrency($model->desconto),
                            'asPopover' => true,
                            'size' => 'md',
                            'name' => 'aux',
                            'formOptions' => ['action' => ['altera-desconto', 'id' => $model->pk_venda]],
                            'pluginEvents' => [
                                "editableSuccess" => "function(event, val, form, data) {"
                                . "$('#valor_final').text(data.valor_final); "
                                . "}",
                            ]
                ]);
                $form = $editable->getForm();
                $editable->beforeInput = '' . $form->field($model, 'desconto')->widget(NumberControl::classname(), [
                            'maskedInputOptions' => [
                                'prefix' => '',
                                'suffix' => '',
                                'allowMinus' => false
                            ],
                ]);
                ;
                Editable::end();
                ?>
            </div>
            <div class="co l-sm-2" style="background-color:lav ender;font-size: 23px">  
                <br>            
                <label>Valor Final:</label>
             <b><span id='valor_final' ><?php echo Yii::$app->formatter->asCurrency($model->valor_final) ?>
                        </span></b>
                
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="co l-sm" style="background-color:lav ender;">  
                <br>
                <?= Html::submitButton(('Pagar'), ['class' => 'btn btn-primary', 'value' => 'pagar', 'name' => 'Venda[button]']) ?> &nbsp;
                <?= Html::submitButton(('Fiado'), ['class' => 'btn btn-success', 'value' => 'fiado', 'name' => 'Venda[button]']) ?>&nbsp;
                <br><br>
                <?= Html::buttonInput(('Comprovante'), ['id' => 'bt_comprovante', 'onClick' => "window.open('comprovante?id={$model->pk_venda}', '_blank');", 'class' => 'btn btn-warning']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
