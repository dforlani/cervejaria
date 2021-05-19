<?php

use app\models\Produto;
use kartik\editable\Editable;
use kartik\number\NumberControl;
use kartik\switchinput\SwitchInput;
use kartik\tabs\TabsX;
use lo\widgets\modal\ModalAjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $model Produto */

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $title . 's', 'url' => [$url_retorno]];
$this->params['breadcrumbs'][] = ['label' => $model->pk_produto, 'url' => ['view', 'id' => $model->pk_produto]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="produto-update">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php
    //ABA PRODUTO
    $produto = "";
    if ($model->isCerveja()) {
        $produto = $this->render('cerveja\_form', [
            'model' => $model,
        ]);
    } else {
        $produto = $this->render('_form', [
            'model' => $model,
        ]);
    }

    //ABA FORMAS DE VENDA
    $forma = $this->render('_forma_venda', [
        'model' => $model, 'dataProviderPreco' => $dataProviderPreco
    ]);

    //ABA ENTRADAS
    $entradas = $this->render('_entradas', [
        'model' => $model, 'dataProviderEntrada' => $dataProviderEntrada
    ]);

    $items = [
        [
            'label' => '<i class="fas fa-home"></i> Produto',
            'content' => $produto,
            'active' => true
        ],
        [
            'label' => '<i class="fas fa-home"></i> Formas de Venda',
            'content' => $forma,
        ],
        [
            'label' => '<i class="fas fa-home"></i> Entradas',
            'content' => $entradas,
        ],
    ];

// Above
    echo TabsX::widget([
        'items' => $items,
        'position' => TabsX::POS_ABOVE,
        'encodeLabels' => false
    ]);
    ?>

</div>

