<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Preco */

$this->title = 'Update Preco: ' . $model->pk_preco;
$this->params['breadcrumbs'][] = ['label' => 'Precos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pk_preco, 'url' => ['view', 'id' => $model->pk_preco]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="preco-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
