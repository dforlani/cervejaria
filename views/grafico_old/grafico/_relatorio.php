<?php

use app\components\Somatorio;
use app\models\VendaSearch;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $searchModel VendaSearch */
/* @var $dataProvider ActiveDataProvider */

?>



<?php
$colunas = [];


if ($por_dia) {
    $colunas[] = [
        'attribute' => 'dt_pagamento',
        'format' => 'date',
        'footer' => 'Total',
        'filter' => false,
    ];
} elseif ($por_mes) {
    $colunas[] = [
        'attribute' => 'dt_pagamento',
        'footer' => 'Total',
        'filter' => false,
    ];
}

if ($por_produto) {
    $colunas[] = 'produto';
    $colunas[] = [
        'attribute' => 'aux_quantidade',
        'format' => 'currency',
        'contentOptions' => ['style' => 'text-align:right'],
        'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'aux_quantidade')),
    ];

    $colunas[] = [
        'attribute' => 'unidade_medida',
        'footer' => ''
    ];
}

if ($por_cliente) {
    $colunas[] = [
        'attribute' => 'nome_cliente',
        'contentOptions' => ['style' => 'text-align:right'],
    ];
}

$colunas[] = [
    'attribute' => 'pagamentos',
    'format' => 'currency',
    'contentOptions' => ['style' => 'text-align:right'],
    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'pagamentos')),
];
$colunas[] = [
    'attribute' => 'pagamentos_liquido',
    'format' => 'currency',
    'contentOptions' => ['style' => 'text-align:right'],
    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'pagamentos_liquido')),
];
?>


<?php
$itens_problema = Somatorio::getTotal($dataProvider->models, 'itens_sem_preco_custo');
echo
GridView::widget([
    'panelBeforeTemplate' => "                               
                               <div class='btn-toolbar kv-grid-toolbar ' role='toolbar'>
                                {export}
                                <b>&nbsp; É considerado para efeito de cálculo a data do fechamento do pagamento</b><br>
                                " . '<b>'
    . '<span style="color:red">&nbsp;&nbsp;' . ( $itens_problema > 0 ? $itens_problema . ' itens nesta página não possuem preço de custo da produção/compra' : '') . '</span></b></div>',
    'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'before' => true,
    ],
    'showFooter' => true,
    'floatHeader' => true,
    'floatHeaderOptions' => ['scrollingTop' => '50', 'position' => 'absolute'],
    'footerRowOptions' => ['style' => 'font-weight:bold;text-align:right;text-decoration: underline;'],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $colunas
]);
?>


</div>
