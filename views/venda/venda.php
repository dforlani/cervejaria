<script>

    $(document).ready(function () {


        $(document).bind('keydown', 'alt+l', function () {
            $('#venda-fk_cliente').select2('open');
        });

        $(document).bind('keydown', 'alt+o', function () {
            $('#venda-fk_comanda').select2('open');
        });

        $(document).bind('keydown', 'alt+v', function () {
            $('#pk_venda_aberta').select2('open');
        });

    });





</script>
<?php
$this->registerJsFile(
        '@web/js/jquery.hotkeys.js'
);
?>

<?php

use app\models\Venda;
use yii\helpers\Html;
use yii\web\View;
use kartik\widgets\Select2;

/* @var $this View */
/* @var $model Venda */

$this->title = 'Balcão';
$this->params['breadcrumbs'][] = ['label' => 'Vendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style='margin:10px'>

</div>
<div class='panel panel-success' >


    <div class="panel-heading">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6" >  
                    <span class="panel-title">            <?= Html::encode($this->title) ?></span>
                </div>               
                <div class="col-sm-6" style="text-align: right">  
                    <?php
                    if (!$model->isNewRecord) {
                        ?>
                        <div type="button" id="bt_comprovante" class="btn btn-danger" value="" accesskey="i" onclick="window.location = './venda'"><u>I</u>niciar Nova Venda</div>
                    <?php } ?>
                </div>

            </div>
        </div>           
    </div>
    <?php if ($model->isNewRecord) { ?>
        <?=
        $this->render('_form_novo', [
            'model' => $model,
        ])
        ?>
        <br>

    <?php } ?>

    <div class = "container-fluid">
        <?php
        echo '<label class="control-label"><u>V</u>endas em Aberto</label>';
        echo Select2::widget([
            'name' => 'kv-type-01',
            'data' => Venda::getArrayVendasEmAberto(),
            'value' => $model->pk_venda,
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
    </div>

    <?php if (!$model->isNewRecord) { ?>     


        <div class="panel-body" style="padding: 5px">
            <div class = "container-fluid">
                <div class = "row">
                    <div class = "col-sm-7" style = "">
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
                    <div class = "col-sm-5" style = "">

                        <div class='row'>
                            <div class='panel panel-danger' style="background-color:#fbddce; ">                               

                                <div class="panel-heading">
                                    <h3 class="panel-title">Tap List</h3>
                                </div>
                                <div class="panel-body" style="">
                                    <?=
                                    $this->render('_tap_list', [
                                        'tapListProvider' => $tapListProvider,
                                        'model' => $model,
                                    ])
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?=
                        $this->render('_form', [
                            'model' => $model,
                        ])
                        ?>
                    </div>
                </div>
            </div>


        </div>
    <?php } ?>


    <br>

</div>


