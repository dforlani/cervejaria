<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
    $(document).ready(function () {
        /**
         * Chamado quando se clica em algum pedido da tabela, busca as informações do pedido no sistema e atualiza a modal
         */
        $(".btn-observacao-venda").click(function (event) {
            event.preventDefault();
            id = $(this).attr('id_venda');
       
            $("#btn_salvar_observacao").attr('id_venda', id);
            $('#text-observacao').val($(this).attr('title'));
      ;

        });
        /**
         * Envia a observação para ser salva no banco
         * @param {type} event
         * @returns {undefined}
         */
        $("#btn_salvar_observacao").click(function (event) {
            event.preventDefault();
            id = $(this).attr('id_venda');
        
            $("#btn-salvar_observacao").attr('id_pedido', id);
            $.get("salvar-observacao", {'id': id,
                '_csrf': '<?= Yii::$app->request->csrfToken ?>',
                observacao: $('#text-observacao').val()})
                    .done(function (data) {
                        $('#modalObervacaoVenda').modal('toggle');
                        location.reload();
                    });

        });

    });
</script>

<div class="row" style='margin-left: 10px;  display: table;' >

    <?php foreach ($vendas as $venda) { ?>
        <a href="./venda?id=<?= $venda->pk_venda ?>" class="link">
            <div type="button" class="btn btn-default" 
                 style='vertical-align: top; margin: 5px;text-align: left;min-width: 250px;min-height:250px;max-height: 100% '>
                <div >
                    <div class="container-fluid" style="padding-right: 0px; padding-left: 0px;">

                        <div class="row">                          
                            <div class="col-sm-6"  style='text-align: left'>
                                <b><?= (!empty($venda->cliente) ? $venda->cliente->nome . '<br> ' : '(Sem nome)<br>' ) ?>
                                    <?= (!empty($venda->comanda) ? $venda->comanda->numero : '(Sem Comanda)' ) ?></b><br><br>
                            </div>
                            <div class="col-sm-6"  style='text-align: right'>
                                <div  style="<?= !empty($venda->observacao)?'color:white;background-color:blue;padding: 10px;font-size: 20px;':'padding: 10px;font-size: 20px;' ?> " title="<?= $venda->observacao?>" id_venda='<?= $venda->pk_venda ?>' type="button" class="btn btn-observacao-venda btn-default glyphicon glyphicon-question-sign"  data-toggle="modal" data-target="#modalObervacaoVenda" ></div>
                            </div>                            
                        </div>
                    </div>


                    <?php
                    //faz a contagem de itens
                    $itens = [];
                    foreach ($venda->itensVenda as $item) {
                        $itens[$item->fk_preco]['nome'] = $item->preco->produto->nome . ' ' . $item->preco->denominacao;
                        $itens[$item->fk_preco]['qtd'] = (isset($itens[$item->fk_preco]['qtd']) ? $itens[$item->fk_preco]['qtd'] + $item->quantidade : $item->quantidade);
                    }

                    foreach ($itens as $item) {
                        ?>
                        <?= $item['qtd'] . ': ' . $item['nome'] ?><br>
                    <?php } ?>


                    <br>

                </div>

                <div>Valor Pago: <b><span style='color:blue'> <?php echo $venda->getValorTotalPago() ?></span></b></div>
                <div>
                    <?php
                    if ($venda->hasFalta())
                        echo "Falta: <b><span style='color:red'> {$venda->getSaldoFormatedBR()}</span></b>";
                    elseif ($venda->hasTroco())
                        echo "Troco: <b><span style='color:green'> {$venda->getSaldoFormatedBR()}</span></b>";
                    else
                        echo $venda->getSaldoFormatedBR();
                    ?>
                </div>
                <br>
                <div>
                    Valor Final: <?= Yii::$app->formatter->asCurrency($venda->valor_final) ?>

                </div>
            </div>
        </a>
    <?php } ?>



</div>

<!-- Modal Observações de Venda -->
<div class="modal fade" id="modalObervacaoVenda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Observação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='divObservacoesVenda'>    
                <textarea id='text-observacao' style='width: 100%'></textarea>

            </div>
            <div class="modal-footer">               
                <button type="button" id_venda=""   id="btn_salvar_observacao" class="btn btn-primary">Salvar</button>               
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
