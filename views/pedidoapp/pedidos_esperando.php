
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
            <div class="panel-body painel-pedido-red" id='div-painel-pedido' >
                <?php foreach ($pedidos as $pedido) { ?>
                    <div id_pedido='<?= $pedido->pk_pedido_app ?>'  id_venda='<?= $pedido->fk_venda ?>'   type="button"  class="btn btn-default btn-pedido" data-toggle="modal" data-target="#modalPedido" style='margin: 5px;text-align: left;max-width: 200px;min-height:100px; '>
                        <b><?= substr($pedido->cliente->nome, 0, 25); ?></b> <br>
                        <b><?= $pedido->status ?></b><br>          

                        <b>Espera: <span id="espera<?= $pedido->pk_pedido_app ?>"></span></b><br>



                        <?php
                        if (!empty($pedido->itensPedidoApp)) {
                            foreach ($pedido->itensPedidoApp as $item) {
                                ?>
                                <?= $item->quantidade . ' - ' . $item->preco->getNomeProdutoPlusDenominacaoSemBarrasLimitado(25) ?><br>    
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
   
            $.get("<?= Url::to(['pedidoapp/pedido-atendimento']); ?>", {'id': id, '_csrf': '<?= Yii::$app->request->csrfToken ?>'})
                    .done(function (data) {
                        $('#divPedidoAtendimento').html(data);
                        $('#btn_pedido_pronto').attr('id_pedido', id);
                        $('#btn_pedido_pronto').attr('id_venda', id_venda);
                        $('#btn-cancelar-pedido').attr('id_pedido', id);
                        $('#btn-cancelar-pedido').attr('id_venda', id_venda);
                    });
          
        });
    });

    pedidos = [<?php
    foreach ($pedidos as $pedido) {
        echo $pedido->getDtPedidoExploded() . ',';
    }
    ?>
    ];
</script>