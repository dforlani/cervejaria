<?php

use yii\helpers\Html;



$this->title = 'Gerenciador de Versões';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    $(document).ready(function () {
        $('.btn').click(function (e) {     
                if (!confirm('Deseja realmente alterar a versão?')) {
                    e.preventDefault();
                }
        });
    });


</script>

<div class="venda-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Clique nos botões para alterar a versão em utilização no momento. <br>
        Este procedimento não faz alterações no Banco de Dados. <br>
        Se a troca for para uma versão maior que 1.2.4, ela poderá ser desfeita a qualquer momento.</h2>
        

    <div class="row" style='margin-left: 10px;  display: table;' >
        <a href="./versoes?versao=master" class="link">
            <div type="button" class="btn btn-primary" style='margin: 5px;text-align: left;min-width: 25px;min-height:50px; '>
                <div >
                    Versão Atual
                </div>               
            </div>
        </a><br>

        <?php foreach ($versoes as $versao) { ?>
            <a href="./versoes?versao=<?= $versao ?>" class="link">
                <div type="button" class="btn btn-success" style='margin: 5px;text-align: left;min-width:  25px;min-height:50px; '>
                    <div >
                        <?= $versao ?>
                    </div>               
                </div>
            </a><br>
        <?php } ?>
    </div>
</div>

