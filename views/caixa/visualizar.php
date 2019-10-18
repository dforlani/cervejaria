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
     <h3>Abertura:<?= Yii::$app->formatter->asDatetime($caixa->dt_abertura)?></h3>
     <h3>Fechamento:<?= Yii::$app->formatter->asDatetime($caixa->dt_fechamento)?></h3>

    <?=
    $this->render('_item_movimento', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'caixa' => $caixa,
        'visualizar'=>true
    ]);
    ?>





</div>
