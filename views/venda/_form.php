
<?php

use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>

<div class="venda-form">

    <div class="container-fluid">
        <div class="row" >
            <div style=";font-size: 20px">  
                <label style="font-size: 23px">  <?= (!empty($model->cliente) ? $model->cliente->nome : '') ?></label><br><br>
                <span>Valor Total:</span>
                <span><?php echo Yii::$app->formatter->asCurrency($model->valor_total) ?></span>
            </div>
            <div style=";font-size: 20px">                  
                <span>Valor Pago:</span>
                <span><?php echo $model->getValorTotalPago() ?></span>
            </div>
            
            
            <div style=";font-size: 20px"> 

            </div>
            <br>
            <div style="font-size: 23px">                  
                <label>Valor Final:</label>
                <b><span id='valor_final' ><?php echo Yii::$app->formatter->asCurrency($model->valor_final) ?></span></b>
            </div >
            <?php $form = ActiveForm::begin(); ?>
             
                <br>
                <?php
                echo ModalAjax::widget([
                    'id' => 'pagamentoModar',
                    'header' => 'Pagar',
                    'toggleButton' => [
                        'label' => '<u>P</u>agar',
                        'class' => 'btn btn-primary pull-left',
                        'accesskey' => "p"
                    ],
                    'url' => Url::to(['pagamento', 'id' => $model->pk_venda]), // Ajax view with form to load
                    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                    'size' => ModalAjax::SIZE_LARGE,
                    'options' => ['class' => 'header-primary',],                    
                    'autoClose' => true,
                    'pjaxContainer' => '#grid-company-pjax',
                ]);

                ?>
                <?php
                Pjax::begin([
                    'id' => 'grid-company-pjax',
                    'timeout' => 5000,
                ]);
                Pjax::end();
                ?>
                &nbsp;&nbsp;
                <div type="button" id="bt_comprovante" class="btn btn-warning" value="" accesskey="c" onclick="window.open('comprovante?id=<?= $model->pk_venda ?>', '_blank');"><u>C</u>omprovante</div>

            
            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>
