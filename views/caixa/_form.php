<?php

use app\models\Caixa;
use kartik\number\NumberControl;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Caixa */
/* @var $form ActiveForm */
?>

<script>
    
    $(document).ready(function () {
        
        $('#tipo').change(function () {
            mostraCategoria($('#tipo :checked').val());
        });

        mostraCategoria('<?= $model->tipo ?>');

    });

    function mostraCategoria(categoria) {
        if (categoria == 'Saída - Pagamento de Despesa') {
            $('#div_categoria').fadeIn();
        } else {
            $('#div_categoria').hide();
        }

    }
</script>
<div class="caixa-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'fk_caixa')->hiddenInput()->label(false) ?>

    <?php
    echo $form->field($model, 'valor_dinheiro')->widget(NumberControl::classname(), [
        'maskedInputOptions' => [
            'prefix' => 'R$ ',
            'suffix' => '',
            'allowMinus' => true
        ],
    ]);
    ?>


    <?= $form->field($model, 'tipo')->radioList(['Sangria' => 'Sangria', 'Saída - Pagamento de Despesa' => 'Saída - Pagamento de Despesa', 'Complementação de Caixa' =>'Complementação de Caixa'], ['prompt' => '', 'id' => 'tipo', 'separator' => '<br>']) ?>

    <div id="div_categoria">
        <?= $form->field($model, 'categoria')->radioList(['Água' => 'Água', 'Luz' => 'Luz', 'Telefone' => 'Telefone', 'Insumos da Fábrica' => 'Insumos da Fábrica', 'Produtos pra Venda' => 'Produtos pra Venda', 'Outra' => 'Outra'], ['prompt' => 'Selecione a categoria de despesa', 'id' => 'tipo', 'separator' => '<br>']) ?>
    </div>

    <?= $form->field($model, 'observacao')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
