<?php

use app\models\Venda;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Venda */
/* @var $form ActiveForm */
?>
<script>

    window.print();

</script>
<div style='width: 250px;margin-left:100px'>
    <?= date('d/m/Y H:i:s', time()); ?><br>
    <div style='text-align:  center'> Cervejaria Paraíso<div>

    Cliente: <?= (!empty($model->cliente) ? $model->cliente->nome : '') ?><br>
    Comanda: <?= (!empty($model->comanda) ? $model->comanda->numero : '') ?><br>
   
 <hr>
    <table style='font-size: 10px'>
        <thead>
            <tr>
                <td style="margin-right: 500px">
                    Item
                </td>
                <td>
                    Qtd
                </td>
                <td>
                   Vl. Unit.
                </td>
                <td>
                     Vl. Item
                </td>
            </tr>
        </thead>
        <tbody>


            <?php
            $itens = $model->itensVenda;
            if (!empty($itens)) {
                foreach ($itens as $item) {
                    ?> 
                    <tr>
                         <td style="margin-right: 50px">
                            <?= $item->preco->getNomeProdutoPlusDenominacao() ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item->quantidade) ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item->preco_unitario) ?>
                        </td>
                         <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item->preco_final) ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <br>
    <hr>
    <div style="text-align: left">
        Valor Total: <?= Yii::$app->formatter->asCurrency($model->valor_total) ?> <br>
    Desconto: <?= Yii::$app->formatter->asCurrency($model->desconto) ?> <br>
    <b>Valor Final</b>: <?= Yii::$app->formatter->asCurrency($model->valor_final) ?> <br>
    <div>
</div>