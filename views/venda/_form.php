
<?php

use lo\widgets\modal\ModalAjax;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
?>
<div class='row'>
    <div class='panel panel-success'>
        <div class="panel-heading">
            <h3 class="panel-title">Pagamento
                <?php
                echo ModalAjax::widget([
                    'id' => 'pagamentoModar',
                    'header' => 'Pagar',
                    'toggleButton' => [
                        'label' => '<u>P</u>agar',
                        'class' => 'btn btn-primary pull-right',
                        'accesskey' => "p"
                    ],
                    'url' => Url::to(['pagamento', 'id' => $model->pk_venda]), // Ajax view with form to load
                    'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
                    'size' => ModalAjax::SIZE_LARGE,
                    'options' => ['class' => 'header-primary',],
                    'autoClose' => true,
                    'pjaxContainer' => '#grid-company-pjax',
                    'events' => [
                        ModalAjax::EVENT_BEFORE_SUBMIT => new \yii\web\JsExpression("
            function(event, xhr, settings) {
                console.log(settings);
            }
        "),
                        ModalAjax::EVENT_MODAL_SUBMIT => new \yii\web\JsExpression("
            function(event, data, status, xhr, selector) {
                console.log(status);
                if(status == 'success'){                  
                   if(data.estado == 'aberta'){
                    location.reload();
                   }
                   else{
                    window.location.replace('./venda');
                   }                   
                }
            }
        "),
                    ]
                ]);
                ?>


                <div type="button" id="bt_comprovante" class="btn btn-warning pull-right" style="margin-right: 15px" accesskey="c" onclick="window.open('comprovante?id=<?= $model->pk_venda ?>', '_blank');"><u>C</u>omprovante</div>
                <br><br></h3>
        </div>
        <div class="panel-body" style="">

            <div class="venda-form">

                <div class="container-fluid">

                    <div class="row">
                        <label style="font-size: 23px">  <?=  \yii\helpers\Html::encode($model->getNomeCliente()) ?></label><br>    
                        <div class="col-sm-6"  style='text-align: right'>

                            <div style=";font-size: 18px">  

                                <span>Total:</span>
                                <span><?php echo Yii::$app->formatter->asCurrency($model->valor_total) ?></span>
                            </div>

                            <div style=";font-size: 18px">                  
                                <span>Pago:</span>
                                <span><?php echo $model->getValorTotalPago() ?></span>
                            </div>




                        </div>
                        <div class="col-sm-6"  style='text-align: right;'>
                            <div style="font-size: 18px;color:blue">                  
                                <label>Final:</label>
                                <b><span id='valor_final' ><?php echo Yii::$app->formatter->asCurrency($model->valor_final) ?></span></b>
                            </div >
                            <div style=";font-size: 18px;">                              
                                <?php
                                
                                if ($model->hasFalta())
                                    echo " <b><span style='color:red'> Falta: {$model->getSaldoFormatedBR()}</span></b>";
                                elseif ($model->hasTroco())
                                    echo " <b><span style='color:green'> Troco: {$model->getSaldoFormatedBR()}</span></b>";
                                else
                                    echo $model->getSaldoFormatedBR();
                                ?>
                            </div>
                        </div>

                    </div>

                    <br>


                    <br>


                    <span style="font-size:20px;color:blue"> <?php echo!empty($model->observacao) ? "<b>Observação: " . $model->observacao . "</b>" : "" ?></span>
                </div>
            </div>
        </div>


    </div>
</div>


