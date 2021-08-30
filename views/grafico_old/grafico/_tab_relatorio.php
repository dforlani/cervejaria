
<?php

use kartik\tabs\TabsX;

$grafico = "";
$relatorio = "";
if (!empty($resultado)) {
    $grafico = $this->render('_grafico', ['resultado' => $resultado]);
    $relatorio = $this->render('_relatorio', ['resultado' => $resultado, 
        'dataProvider' => $dataProvider,
        'por_forma_venda' => $por_forma_venda,
        'cervejas' => $cervejas,
        'cervejas_selecionadas' => $cervejas_selecionadas,
        'por_gasto' => $por_gasto,
        'por_litro' => $por_litro,
        'resultado' => $resultado,
        'por_hora' => $por_hora,
        'por_dia_semana' => $por_dia_semana,
        'por_dia' => $por_dia,
        'por_mes_agregado' => $por_mes_agregado,
        'por_mes' => $por_mes,
        'por_produto' => $por_produto,
        'por_cliente' => $por_cliente,
        'apenas_vendas_pagas' => $apenas_vendas_pagas,
        'data_inicial' => $data_inicial,
        'data_final' => $data_final]);
}

$items = [
    [
        'label' => 'Gráfico ',
        'content' => $grafico,
        'active' => true,
    ],
    [
        'label' => ' Relatório ',
        'content' => $relatorio,
        'active' => false,
    ],
];

echo TabsX::widget([
    'bordered' => true,
    'items' => $items,
    'position' => TabsX::POS_ABOVE,
    'encodeLabels' => false,
    'id' => "tabsTermo"
]);
?>

