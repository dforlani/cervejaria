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

<div class="venda-form">

    <?php $form = ActiveForm::begin(); ?>


        <div class = "container-fluid">
            <div class = "row">
                <div class = "col-sm-4" style = "">
                    <?php
                    echo $form->field($model, 'fk_cliente')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Cliente::getClientesSemVendasAbertas(), 'pk_cliente', 'nome'),
                        'options' => ['placeholder' => 'Selecione um cliente'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Cliente');
                    ?>
                </div>
                <div class="col-sm-4" style=""> 
                    <?php
                    echo $form->field($model, 'fk_comanda')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Comanda::getComandasSemVendasAbertas(), 'pk_comanda', 'numero'),
                        'options' => ['placeholder' => 'Selecione um cliente'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label('Comanda');
                    ?>
                </div>
                <div class="col-sm-4" style=""> 
                    <br>
                    <?= Html::submitButton(( 'Nova Venda'), ['class' => 'btn btn-success']) ?>
                </div>

            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
