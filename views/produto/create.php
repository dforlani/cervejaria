<?php

use yii\helpers\Html;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model app\models\Produto */

$this->title = 'Novo Produto';
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="produto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php 
//
//// Above
//echo TabsX::widget([
//    'items'=>$items,
//    'position'=>TabsX::POS_ABOVE,
//    'encodeLabels'=>false
//]);