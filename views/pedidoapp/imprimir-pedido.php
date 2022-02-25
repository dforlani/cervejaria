<script>
    window.print();
</script>


<div style='width: 150px; margin-left: 20px; font-size:10px'>
    <?php
    $d = new DateTime('now', new DateTimeZone('America/Bahia'));
    ?>


    <?php if (!empty($model->venda->comanda)) { ?>
        Comanda: <?= \yii\helpers\Html::encode($model->venda->comanda->numero) ?> <br>
    <?php } ?>

    <?php if (!empty($model->venda->cliente)) { ?>
        Cliente: <?= \yii\helpers\Html::encode($model->venda->cliente->nome) ?> <br>
    <?php } ?>

    <?php if (!empty($model->venda->nome_temp)) { ?>
        Cliente: <?= \yii\helpers\Html::encode($model->venda->nome_temp) ?> <br>
    <?php } ?>

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
                    Total Item
                </td>
            </tr>
        </thead>
        <tbody>


            <?php
            $itens = $model->itensPedidoApp;
            if (!empty($itens)) {
                foreach ($itens as $item) {
                    ?> 
                    <tr>
                        <td style="margin-right: 50px">
                            <?= $item->preco->getNomeProdutoPlusDenominacao() ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item->quantidade) ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item->preco->preco) ?>
                        </td>
                        <td style='text-align: right'>
                            <?= Yii::$app->formatter->asCurrency($item->preco->preco * $item->quantidade ) ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <br>
    <?php if(!empty($model->observacoes)){ ?>
    <b>Observações: <?= $model->observacoes ?> </b><br>
    <?php } ?>
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
