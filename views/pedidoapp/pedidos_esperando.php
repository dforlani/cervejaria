
<?php

use yii\helpers\Url;
?>

<style>
    .painel-pedido{
        background-color: red
    }

</style>
<?php
if (!empty($pedidos)) {
    ?>
    <div class='row'>
        <div class='panel panel-danger' >                               

            <div class="panel-heading">
                <h3 class="panel-title">Pedidos</h3>
            </div>
            <div class="panel-body painel-pedido" id='div-painel-pedido' >
                <?php foreach ($pedidos as $pedido) { ?>
                    <div id_pedido='<?= $pedido->pk_pedido_app ?>'  id_venda='<?= $pedido->fk_venda ?>'   type="button"  class="btn btn-default btn-pedido" data-toggle="modal" data-target="#modalPedido" style='margin: 5px;text-align: left;max-width: 200px;min-height:100px; '>
                        <b><?= $pedido->cliente->nome; ?></b> <br>
                        <b><?= $pedido->status ?></b><br>          

                        <b>Espera: <span id="espera<?= $pedido->pk_pedido_app ?>"></span></b><br>



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

//timer pra tela de pedidos ficar piscando e ficar contando o tempo de espera
    pedidos = [<?php foreach ($pedidos as $pedido) { ?>
    <?= $pedido->getDtPedidoExploded() ?>,
<?php } ?>
    ];
    var myVar = setInterval(myTimer, 1000);
    var d, displayDate;
    function myTimer() {
        pedidos.forEach(minusDate);
        $('#div-painel-pedido').toggleClass('painel-pedido');
    }

    function minusDate(value, index, array) {

        d = new Date();
        d.setDate(d.getDate() - value.dia);
        d.setMonth(d.getMonth() - value.mes);
        d.setYear(d.getYear() - value.ano);
        d.setHours(d.getHours() - value.hora);
        d.setMinutes(d.getMinutes() - value.minuto);
        d.setSeconds(d.getSeconds() - value.segundo);
        if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
            displayDate = d.toLocaleTimeString('pt-BR');
        } else {
            displayDate = d.toLocaleTimeString('pt-BR', {timeZone: 'America/Belem'});
        }
        document.getElementById("espera" + value.pk_pedido_app).innerHTML = displayDate;
    }
</script>