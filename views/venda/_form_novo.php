<?php

use app\models\Venda;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Venda */
/* @var $form ActiveForm */
?>

<script>
    $(document).ready(function(){
        $('#btn_submit').click(function(e){
            if($('#venda-fk_cliente').val() == ''){
                if(!confirm('Cliente sem nome. Confirma?')){
                    e.preventDefault(); 
                }
            }
        
        });
    });
        
    
    </script>

<div class="venda-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class = "container-fluid">
        <div class = "row">
            <div class = "col-sm-4" style = "">
                <?php
                echo $form->field($model, 'fk_cliente')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Cliente::getClientesSemVendasAbertas(), 'pk_cliente', 'nome'),
                    'options' => ['placeholder' => 'Selecione um cliente',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                        // 'options' => []
                ])->label('C<u>l</u>iente');
                ?>
            </div>
            <div class="col-sm-4" style=""> 
                <?php
                echo $form->field($model, 'fk_comanda')->widget(Select2::classname(), [
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Comanda::getComandasSemVendasAbertas(), 'pk_comanda', 'numero'),
                    'options' => ['placeholder' => 'Selecione uma comanda'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('C<u>o</u>manda');
                ?>
            </div>
            <div class="col-sm-4" style=""> 
                <br>
                <?= Html::submitButton(( '<u>N</u>ova Venda'), ['id'=>'btn_submit',  'accesskey'=>"n", 'class' => 'btn btn-success']) ?>
            </div>

        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs(
        "$(document).ready(function(){
       $('#pk_venda_aberta').select2('open');
    });", View::POS_READY, 'my-button-handler'
);
