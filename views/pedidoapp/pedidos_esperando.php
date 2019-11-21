
<?php

use yii\helpers\Url;
?>


<?php
if (!empty($pedidos)) {
    ?>
    <div class='row'>
        <div class='panel panel-danger' >                               

            <div class="panel-heading">
                <h3 class="panel-title">Pedido</h3>
            </div>
            <div class="panel-body" style='background-color: #ff704d'>
                    <?php foreach ($pedidos as $pedido) { ?>
                    <div id_pedido='<?= $pedido->pk_pedido_app ?>' type="button"  class="btn btn-default" data-toggle="modal" data-target="#modalPedido" style='margin: 5px;text-align: left;min-width: 100px;min-height:100px; '>
                        <b><?= $pedido->status ?></b><br>    
                        <b><?= $pedido->cliente->nome; ?></b> <br>
                        <?php
                        if (!empty($pedido->itensPedidoApp)) {
                            foreach ($pedido->itensPedidoApp as $item) {
                                ?>
                                <?= $item->quantidade . ' - ' . $item->preco->getNomeProdutoPlusDenominacaoSemBarras() ?><br>    
                                <?php
                            }
                        }
                        ?>
                    </div>
                <?php }
                ?>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Modal Pedido -->
<div class="modal fade" id="modalPedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='divPedidoAtendimento'>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Pronto</button>
                <button type="button" class="btn btn-danger">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(".btn.btn-default").click(function () {
        id = $(this).attr('id_pedido');
        console.log(id);
        $.get("<?= Url::to(['pedidoapp/pedido-atendimento']); ?>", {'id': id, '_csrf': '<?= Yii::$app->request->csrfToken ?>'})
                .done(function (data) {
                    $('#divPedidoAtendimento').html(data);
                });
    });
</script>
