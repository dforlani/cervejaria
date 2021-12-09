<style >
    .titulo_1{
        font-size:30px;
        font-weight: bold;
        text-align: center;
        font-family:'Trebuchet MS', sans-serif;
    }

    .titulo_2{
        font-weight: bold;
        font-family:'Trebuchet MS', sans-serif;
        font-size:25px;
        text-align: center
    }

    .produto{

        font-family:'Arial';
        font-size:15px;

    }

    dl { width: 500px }
    dt {font-size:20px; float: left; width: 500px; overflow: hidden; white-space: nowrap }
    dd {font-size:20px; float: right; text-align:right; width: 100px; overflow: hidden }

    dt:after { content: " ..........................................................................................................." }
</style>

<div style="width:600px">

    <table style="width:100%">
        <tr>
            <td> <img src="<?= Yii::getAlias('@webroot') ?>\images\cerveja.jpg" style="width:150px"/></td>
            <td> <p class="titulo_1">CERVEJARIA PARAÍSO</p><br></td>          
        </tr>   
    </table>


    <?php if (!empty($aperitivos)) { ?>
        <p class="titulo_2">APERITIVOS</p>

        <?php
        foreach ($aperitivos as $item) {
            foreach ($item->precos as $preco) {
                if ($preco->ativo)
                    echo "<p class='produto'>" . $preco->getNomeProdutoPlusDenominacaoSemBarras() . '<dottab outdent="3em" /> R$ ' . $preco->preco . '</p>';
            }
        }
        ?>

        <p style="font-size: 12px"> Obs: Porcão de bolinho e quibe com 15 unidades cada.</p>
        <?php
    }
    if (!empty($bebidas)) {
        ?>
        <br>
        <p class="titulo_2">BEBIDAS</p>


        <?php
        foreach ($bebidas as $item) {
            foreach ($item->precos as $preco) {
                if ($preco->ativo)
                    echo "<p class='produto'>" . $preco->getNomeProdutoPlusDenominacaoSemBarras() . '<dottab outdent="3em" /> R$ ' . $preco->preco . '</p>';
            }
        }
    }
    if (!empty($drinks)) {
        ?>
        <br>
        <p class="titulo_2">DRINKS</p>



        <?php
        foreach ($drinks as $item) {
            foreach ($item->precos as $preco) {
                if ($preco->ativo)
                    echo "<p class='produto'>" . $preco->getNomeProdutoPlusDenominacaoSemBarras() . '<dottab outdent="3em" /> R$ ' . $preco->preco . '</p>';
            }
        }
    }


    if (!empty($cervejas)) {
        ?>
        <br>
        <p class="titulo_2">CERVEJAS</p>


        <?php
        foreach ($cervejas as $item) {
            foreach ($item->precos as $preco) {
                if ($preco->ativo)
                    echo "<p class='produto'>" . $preco->getNomeProdutoPlusDenominacaoSemBarras() . '<dottab outdent="3em" /> R$ ' . $preco->preco . '</p>';
            }
        }
    }
    ?>
</div>


