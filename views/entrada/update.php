<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Entrada */

$this->title = 'Update Entrada: ' . $model->pk_entrada;
$this->params['breadcrumbs'][] = ['label' => 'Entradas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pk_entrada, 'url' => ['view', 'id' => $model->pk_entrada]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entrada-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
