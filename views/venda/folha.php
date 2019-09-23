<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="row" style='margin-left: 10px;  display: table;' >

    <?php foreach ($vendas as $venda) { ?>
    <a href="./venda?id=<?= $venda->pk_venda?>" class="link">
        <div type="button" class="btn btn-default" style='margin: 5px;text-align: left;min-width: 250px;min-height:250px; '>
            <div >
                <b><?= (!empty($venda->cliente) ? $venda->cliente->nome . '<br> ' : '(Sem nome)<br>' ) ?><?= (!empty($venda->comanda) ? $venda->comanda->numero : '(Sem Comanda)' ) ?></b><br><br>
                <?php
                //faz a contagem de itens
                $itens = [];
                foreach ($venda->itensVenda as $item) {
                    $itens[$item->fk_preco]['nome'] = $item->preco->produto->nome . ' ' . $item->preco->denominacao;
                    $itens[$item->fk_preco]['qtd'] = (isset($itens[$item->fk_preco]['qtd']) ? $itens[$item->fk_preco]['qtd'] + $item->quantidade : $item->quantidade);
                }

                foreach ($itens as $item) {
                    ?>
                    <?= $item['qtd']. ': '.$item['nome']   ?><br>
                <?php } ?>


                <br>

            </div>
            
             <?= $venda->getTroco() ?>
           
            <div style='  '>
                Total:<?= $venda->valor_final ?>
            </div>
        </div>
    </a>
    <?php } ?>



</div>
