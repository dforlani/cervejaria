<?php

use kartik\tabs\TabsX;
$items = [
    [
        'label' => '<i class="fas fa-home"></i> Produto',
        'content' => "Prt",
        'active' => true
    ],
    [
        'label' => '<i class="fas fa-home"></i> Formas de Venda',
        'content' => "sdfsdf",
    ],
    [
        'label' => '<i class="fas fa-home"></i> Entradas',
        'content' => "sdfsdf",
    ],
];

// Above
echo TabsX::widget([
    'items' => $items,
    'position' => TabsX::POS_ABOVE,
    'encodeLabels' => false
]);
