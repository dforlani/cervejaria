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

<div style='width: 150px; margin-left: 20px; font-size:10px'>
    <?= Yii::$app->formatter->asDatetime(date('Y-m-d H:i:s')); ?><br>
    <div style='text-align:center; font-size:14px'> Cervejaria Paraíso</div>

    Cliente: <?= (!empty($model->cliente) ? $model->cliente->nome : '') ?><br>
    Comanda: <?= (!empty($model->comanda) ? $model->comanda->numero : '') ?><br>

    <hr>
    <table style='font-size:11px'> 
        <thead>
            <tr>
                <td style="margin-right:10px; margin-left:10px">
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
                //gera os totais
                $totais = [];
                foreach ($itens as $item) {
                    $totais[$item->fk_preco]['nome'] = $item->preco->getNomeProdutoPlusDenominacaoSemBarras();
                    $totais[$item->fk_preco]['quantidade'] = (isset($totais[$item->fk_preco]['quantidade']) ? $totais[$item->fk_preco]['quantidade'] + $item->quantidade : $item->quantidade);
                    $totais[$item->fk_preco]['preco_unitario'] = $item->preco_unitario;
                    $totais[$item->fk_preco]['preco_final'] = (isset($totais[$item->fk_preco]['preco_final']) ? $totais[$item->fk_preco]['preco_final'] + $item->preco_final : $item->preco_final);
                }



                foreach ($totais as $item) {
                    ?> 
                    <tr>
                        <td style="margin-right: 50px">
                            <?= $item['nome'] ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item['quantidade']) ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item['preco_unitario']) ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item['preco_final']) ?>
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
        Pago:<?php echo $model->getValorTotalPago() ?><br>
        <?php
        if ($model->hasFalta())
            echo "Falta:  {$model->getSaldoFormatedBR()}";
        elseif ($model->hasTroco())
            echo "Troco:{$model->getSaldoFormatedBR()}";
        else
            echo $model->getSaldoFormatedBR();
        ?><br>
        <b>Valor Final</b>: <?= Yii::$app->formatter->asCurrency($model->valor_final) ?> <br>    
    </div>
</div>
<script>
    var i = 0;
    //por algum motivo não sabido, chamar o window.close diretamente passou a fechar a tela sem esperar o print
    //mas colocando ele dentro do setInterval, ele espera
    var loop = setTimeout(function () {

        window.close();

    }, 1000);

    //outra variação pra funcionar em outros browsers
    window.onfocus = function () {
        setTimeout(function () {
            window.close();
        }, 500);
    }


  
</script>
