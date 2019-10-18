<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$total_dinheiro = 0;
$total_credito = 0;
$total_debito = 0;
$total_despesa = 0;
$total_sangria = 0;
$total_abertura = 0;

foreach ($itens as $item) {
    if ($item->isEntrada()) {
        $total_dinheiro += $item->valor_dinheiro;
        $total_credito += $item->valor_credito;
        $total_debito += $item->valor_debito;
    }


    $total_abertura += $item->isAberturaCaixa() ? $item->valor_dinheiro : 0;
    $total_despesa += $item->isPgtoDespesa() ? $item->valor_dinheiro : 0;
    $total_sangria += $item->isSangria() ? $item->valor_dinheiro : 0;
}
?>
<br>

<div class='row'>
    <div class='panel panel-success'>
        <div class="panel-heading">
            <h3 class="panel-title">Totais</h3>
        </div>
        <div class="panel-body" style="text-align: right">
            <b>Abertura:</b> <?= Yii::$app->formatter->asCurrency($total_abertura) ?><br><br>
            <span style="color:blue">
            <b> Entrada - Recebimento de Pagamentos</b>
            <b>Dinheiro:</b> <?=  Yii::$app->formatter->asCurrency($total_dinheiro) ?><br>
            <b>Débito:</b> <?=  Yii::$app->formatter->asCurrency($total_debito) ?><br>
            <b>Crédito:</b> <?=  Yii::$app->formatter->asCurrency($total_credito) ?><br>
            <b>Total:</b> <?=  Yii::$app->formatter->asCurrency($total_dinheiro + $total_credito + $total_debito) ?><br></span>
            <br>
            <span style='color:red'>
            <b>Saídas - Pagamento de Despesas:</b> <?=  Yii::$app->formatter->asCurrency($total_despesa) ?><br><br>
            <b>Sangrias:</b> <?=  Yii::$app->formatter->asCurrency($total_sangria) ?><br>
            </span><br>
             <span style=''>
                 
            <b>Caixa Fechado com Valor em Dinheiro de:</b> <?=  Yii::$app->formatter->asCurrency( $total_abertura +$total_despesa + $total_sangria + $total_dinheiro) ?><br><br>            
            </span>
        </div>
    </div>
</div>