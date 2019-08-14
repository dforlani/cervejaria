<?php

use app\models\Preco;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model Preco */

$this->title = 'Atualizar Forma de Venda ' ;
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
