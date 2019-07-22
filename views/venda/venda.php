<script>

    $(document).ready(function () {
        $('#venda-desconto').change(function () {
           if (($('venda-desconto').val() != '') && ($('#venda-valor_total').val() != '')) {
            $('#venda-valor_final').val( parseFloat($('#venda-valor_total').val()) - parseFloat($('#venda-desconto').val()));
        } else {
            $('#venda-valor_final').val($('#venda-valor_total').val());
        }
        });

    });

   
 
</script>

<?php
//

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Venda */

$this->title = 'Balcão';
$this->params['breadcrumbs'][] = ['label' => 'Vendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venda-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>

<div class="item-insert">

    <h1>Itens</h1>

    <?php
    if(!empty($modelItem))
    echo $this->render('item/_item', [
         'model' => $model,
        'modelItem' => $modelItem,
        'dataProviderItem' => $dataProviderItem,
        'searchModelItem' => $searchModelItem,
    ])
    ?>

</div>