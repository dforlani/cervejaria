<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Papel */

$this->title = 'Create Papel';
$this->params['breadcrumbs'][] = ['label' => 'Papels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="papel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
