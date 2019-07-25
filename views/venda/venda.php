<script>

    $(document).ready(function () {
        $('#venda-desconto').change(function () {
            if (($('venda-desconto').val() != '') && ($('#venda-valor_total').val() != '')) {
                $('#venda-valor_final').val(parseFloat($('#venda-valor_total').val()) - parseFloat($('#venda-desconto').val()));
            } else {
                $('#venda-valor_final').val($('#venda-valor_total').val());
            }
        });

    });

    function changeVendaAberta() {
        console.log('oi');
    }



</script>

<?php

//


use app\models\Venda;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Venda */

$this->title = 'Balcão';
$this->params['breadcrumbs'][] = ['label' => 'Vendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style='margin:10px'>

</div>
<div class='panel panel-success' >
    <?php
        echo '<label class="control-label">Vendas em Aberto</label>';
        echo Select2::widget([
            'name' => 'kv-type-01',
            'data' => Venda::getArrayVendasEmAberto(),
            'options' => [
                'id' => 'pk_venda_aberta',
                'style' => '',
                'placeholder' => 'Selecione uma venda aberta',
            ],
            'pluginEvents' => [
                "change" => "function(e) { window.location='./venda?id='+$('#pk_venda_aberta').val(); }  ",
            ]
        ]);
        ?>

    <div class="panel-heading">
        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
       
    </div>
    <div class="panel-body" style="padding: 5px">
        <div class="venda-create">



            <?=
            $this->render('_form', [
                'model' => $model,
            ])
            ?>

        </div>

        <div class="item-insert">



            <?php
            if (!empty($modelItem))
                echo $this->render('item/_item', [
                    'model' => $model,
                    'modelItem' => $modelItem,
                    'dataProviderItem' => $dataProviderItem,
                    'searchModelItem' => $searchModelItem,
                ])
                ?>

        </div>
    </div>
    <div class="pager" style='margin-left: 50px'>

    </div>
</div>


