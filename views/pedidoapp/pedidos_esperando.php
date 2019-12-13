
<?php

use yii\helpers\Url;
?>


<?php
if (!empty($pedidos)) {
    ?>
    <div class='row'>
        <div class='panel panel-danger' >                               

            <div class="panel-heading">
                <h3 class="panel-title">Pedidos</h3>
            </div>
            <div class="panel-body" style='background-color: red'>
                <?php foreach ($pedidos as $pedido) { ?>
                    <div id_pedido='<?= $pedido->pk_pedido_app ?>'  id_venda='<?= $pedido->fk_venda ?>'   type="button"  class="btn btn-default btn-pedido" data-toggle="modal" data-target="#modalPedido" style='margin: 5px;text-align: left;max-width: 200px;min-height:100px; '>
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



<script>
    $(document).ready(function () {
        /**
         * Chamado quando se clica em algum pedido da tabela, busca as informações do pedido no sistema e atualiza a modal
         */
        $(".btn-pedido").click(function () {
            id = $(this).attr('id_pedido');
            id_venda = $(this).attr('id_venda');
            console.log(id);
            $.get("<?= Url::to(['pedidoapp/pedido-atendimento']); ?>", {'id': id, '_csrf': '<?= Yii::$app->request->csrfToken ?>'})
                    .done(function (data) {
                        $('#divPedidoAtendimento').html(data);
                        $('#btn_pedido_pronto').attr('id_pedido', id);
                        $('#btn_pedido_pronto').attr('id_venda', id_venda);

                        $('#btn-cancelar-pedido').attr('id_pedido', id);
                        $('#btn-cancelar-pedido').attr('id_venda', id_venda);
                    });
            // $('#modalPedido').modal('show');
        });


    });
</script>
