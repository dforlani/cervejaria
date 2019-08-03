<?php

use app\models\Preco;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Preco */
/* @var $form ActiveForm */
?>
<script>
    $(document).ready(function(){
        $('#sugestao').click(function(){
            $('#preco-codigo_barras').val( $('#sugestao').attr('value'));
        });
    });
    </script>
<div class="preco-form">

    <?php $form = ActiveForm::begin(); ?>



    <?= $form->field($model, 'fk_produto')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'denominacao')->textInput(['maxlength' => true, 'autofocus' => '']) ?>
    <?= $form->field($model, 'preco')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'codigo_barras')->textInput(['maxlength' => true]) ?>
       <button type="button" id="sugestao" value='<?= Preco::getSugestaoCodigoBarras() ?>'>   Usar código de barras sugerido:&nbsp; <?= Preco::getSugestaoCodigoBarras() ?></button>
       <br><br>
    <?=
    $form->field($model, 'quantidade', [
        'addon' => [
            //'prepend' => ['content' => '$', 'options'=>['class'=>'alert-success']],
            'append' => ['content' => 'em ' . $model->produto->unidadeMedida->unidade_medida, 'options' => ['style' => 'font-family: Monaco, Consolas, monospace;']],
        ]
    ]);
    ?>
 


    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
