<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Comanda */

$this->title = 'Update Comanda: ' . $model->pk_comanda;
$this->params['breadcrumbs'][] = ['label' => 'Comandas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pk_comanda, 'url' => ['view', 'id' => $model->pk_comanda]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="comanda-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
