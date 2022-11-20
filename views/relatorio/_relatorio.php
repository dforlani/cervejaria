<?php

use app\components\Somatorio;
use app\models\VendaSearch;
use kartik\field\FieldRange;
use kartik\widgets\SwitchInput;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel VendaSearch */
/* @var $dataProvider ActiveDataProvider */
?>



<?php
$colunas = [];

$colunas[] = [
    'attribute' => 'aux_temporizador',
    'footer' => 'Total',
    'filter' => false,
    'header' => "Data"
];


if ($por_produto) {
    $colunas[] = ['attribute' => 'aux_nome_produto',
        'header' => 'Produto'];
    $colunas[] = [
        'attribute' => 'aux_quantidade',
        'header' => 'Quantidade',
        'format' => 'currency',
        'contentOptions' => ['style' => 'text-align:right'],
        'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'aux_quantidade')),
    ];

    $colunas[] = [
        'attribute' => 'unidade_medida',
        'footer' => ''
    ];
}

if ($por_forma_venda) {
    $colunas[] = ['attribute' => 'aux_nome_produto',
        'header' => 'Produto'];
    $colunas[] = [
        'attribute' => 'aux_quantidade',
        'header' => 'Quantidade',
        'format' => 'currency',
        'contentOptions' => ['style' => 'text-align:right'],
        'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'aux_quantidade')),
    ];

    $colunas[] = [
        'attribute' => 'unidade_medida',
        'footer' => ''
    ];
}





if ($apenas_cervejas && !$por_forma_venda && !$por_produto) {
    $colunas[] = [
        'attribute' => 'aux_quantidade',
        'header' => 'Quantidade',
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
        'attribute' => 'aux_nome_cliente',
        'header' => 'Cliente',
        'contentOptions' => ['style' => 'text-align:right'],
    ];
}

$colunas[] = [
    'attribute' => 'pagamentos_bruto',
    'format' => 'currency',
    'contentOptions' => ['style' => 'text-align:right'],
    'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'pagamentos_bruto')),
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
    'columns' => $colunas
]);
?>


</div>
