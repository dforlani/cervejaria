<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Venda */

$this->title = 'Update Venda: ' . $model->pk_venda;
$this->params['breadcrumbs'][] = ['label' => 'Vendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pk_venda, 'url' => ['view', 'id' => $model->pk_venda]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="venda-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
