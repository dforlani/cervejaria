<?php

use app\models\CaixaSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel CaixaSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Visualizar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caixa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_item_movimento', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'caixa' => $caixa,
        'visualizar'=>true
    ]);
    ?>





</div>
