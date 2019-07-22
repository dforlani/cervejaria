<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItemVenda */

$this->title = 'Update Item Venda: ' . $model->fk_venda;
$this->params['breadcrumbs'][] = ['label' => 'Item Vendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fk_venda, 'url' => ['view', 'id' => $model->fk_venda]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-venda-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
