<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UnidadeMedida */

$this->title = 'Update Unidade Medida: ' . $model->pk_unidade_medida;
$this->params['breadcrumbs'][] = ['label' => 'Unidade Medidas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pk_unidade_medida, 'url' => ['view', 'id' => $model->pk_unidade_medida]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="unidade-medida-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
