<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Caixa */

$this->title = 'Movimento de Caixa';
$this->params['breadcrumbs'][] = ['label' => 'Caixas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pk_caixa, 'url' => ['view', 'id' => $model->pk_caixa]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="caixa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
