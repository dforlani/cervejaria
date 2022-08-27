<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<h3>
    <?php if (!empty($model->venda->fk_comanda)) {
        ?>
        <b><?= $model->venda->comanda->numero; ?></b> <br>
    <?php } ?>

    <?php if ($model->venda->hasNomeCliente()) {
        ?>
        <b><?= $model->venda->getNomeCliente() ?></b> <br>
    <?php } ?>
</h3>
<br>
<form id='form-atendimento-app'>
    <input type='hidden' name='_csrf' value="<?= Yii::$app->request->csrfToken ?>"    />
    <input type='hidden' name='id_venda' value="<?= $model->fk_venda ?>"    />
    <input type='hidden' name='id_pedido' value="<?= $model->pk_pedido_app ?>"    />
    <?php
    if (!empty($model->itensPedidoApp)) {
        foreach ($model->itensPedidoApp as $item) {
            ?>
            <div>
                <input type="checkbox" id="item-venda-<?= $item->pk_item_pedido_app ?>" name="ItemPedido[<?= $item->pk_item_pedido_app ?>]"
                       checked>
                <label for="scales">  <?= $item->quantidade . ' - ' . $item->preco->getNomeProdutoPlusDenominacaoSemBarras() ?></label>
            </div>  
            <?php
        }
    }
    if (!empty($model->observacoes)) {
        ?>
        <br>

        <b>Observações</b>: <?= $model->observacoes ?>
    <?php } ?>
</form>