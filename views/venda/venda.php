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





</script>

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
                <div class="col-sm-6" style="background-color:laven der;">  
                    <span class="panel-title">            <?= Html::encode($this->title) ?></span>
                </div>               
                <div class="col-sm-6" style="text-align: right">  
                    <?php
                    if (!$model->isNewRecord)
                        echo Html::buttonInput(('Iniciar Nova Venda'), [  'accesskey'=>"i", 'onClick' => "window.location='./venda'", 'class' => 'btn btn-danger'])
                        ?>
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
   
     <?php }?>
    
    <div class = "container-fluid">
        <?php
        echo '<label class="control-label">Vendas em Aberto</label>';
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
   
    <?php if (!$model->isNewRecord) {?>     
    

        <div class="panel-body" style="padding: 5px">
            <div class = "container-fluid">
                <div class = "row">
                    <div class = "col-sm-9" style = "">
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
                    <div class = "col-sm-3" style = "">
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




</div>


