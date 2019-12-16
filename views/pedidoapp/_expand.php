<?php

use kartik\editable\Editable;
use kartik\number\NumberControl;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
?>

<table class="table table-bordered table-condensed table-hover small kv-table">
    <tbody><tr class="danger">
            <th colspan="4" class="text-left text-danger">Itens Pedidos</th>
        </tr>
        <tr class="active">
            <th> Denominação</th>          
            <th >Quantidade</th>          
        </tr>
        <?php foreach ($model->itensPedidoApp as $item) { ?>
            <tr>
                <td> <?= $item->preco->getNomeProdutoPlusDenominacaoSemBarras()?></td>
                <td> <?= $item->quantidade?></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
