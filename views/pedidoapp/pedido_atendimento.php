<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<b><?= $model->status ?></b><br>    
<b><?= $model->cliente->nome; ?> </b><br>
<?php
if (!empty($model->itensPedidoApp)) {
    foreach ($model->itensPedidoApp as $item) {
        ?>
        <?= $item->quantidade . ' - ' . $item->preco->getNomeProdutoPlusDenominacaoSemBarras() ?><br>    
        <?php
    }
}
?>