<?php

use app\components\Somatorio;
use app\models\VendaSearch;
use kartik\field\FieldRange;
use kartik\widgets\SwitchInput;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel VendaSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Relatórios de Vendas';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    $(document).ready(function () {
        //não vai permitir que por_dia e por_mes estajam ligados ao mesmo tempo
        $('#por_dia').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data)
                $('#por_mes').bootstrapSwitch('state', !data, true);

        });

        //não vai permitir que por_dia e por_mes estajam ligados ao mesmo tempo
        $('#por_mes').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data)
                $('#por_dia').bootstrapSwitch('state', !data, true);

        });
    });
</script>


<div class="venda-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <form>
        <div class="row">
            <div class="col-md-2">
                <?php
                echo '<label class="control-label">Por dia</label>';
                echo SwitchInput::widget(['name' => 'por_dia', 'id' => 'por_dia', 'value' => $por_dia]);
                ?>
            </div>

            <div class="col-md-2">
                <?php
                echo '<label class="control-label">Por Mês</label>';
                echo SwitchInput::widget(['name' => 'por_mes', 'id' => 'por_mes', 'value' => $por_mes]);
                ?>
            </div>

            <div class="col-md-2">
                <?php
                echo '<label class="control-label">Por Produto</label>';
                echo SwitchInput::widget(['name' => 'por_produto', 'value' => $por_produto]);
                ?>

            </div>

            <div class="col-md-2">
                <?php
                echo '<label class="control-label">Por Cliente</label>';
                echo SwitchInput::widget(['name' => 'por_cliente', 'value' => $por_cliente]);
                ?>

            </div>

            <div class="col-md-3">
                <?php
                echo '<label class="control-label">Apenas Vendas Pagas</label>';
                echo SwitchInput::widget(['name' => 'apenas_vendas_pagas', 'value' => $apenas_vendas_pagas]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5" id='faixa_diaria'>
                <?php
                echo FieldRange::widget([
                    // 'form' => $form,
                    // 'model' => $model,
                    'label' => 'Selecione a faixa de tempo',
                    'name1' => 'data_inicial',
                    'value1' => $data_inicial,
                    'name2' => 'data_final',
                    'value2' => $data_final,
                    'separator' => ' ← até →',
                    'type' => FieldRange::INPUT_DATE,
                ]);
                ?>
            </div>
        </div>

        <?= Html::submitButton('<u>G</u>erar', ['accesskey' => 'g', 'class' => 'btn btn-success']) ?>
    </form><br>

    <?php
    $colunas = [];


    if ($por_dia) {
        $colunas[] = [
            'attribute' => 'dt_venda',
            'format' => 'date',
            'footer' => 'Total',
            'filter' => false,
        ];
    } elseif ($por_mes) {
        $colunas[] = [
            'attribute' => 'dt_venda',
            'footer' => 'Total',
            'filter' => false,
        ];

        //$colunas[] = 'pagamentos:currency';
    }

    if ($por_produto) {
        $colunas[] = 'produto';
        $colunas[] = [
            'attribute' => 'quantidade',
            'format' => 'currency',
            'contentOptions' => ['style' => 'text-align:right'],
            'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'quantidade')),
        ];

        $colunas[] = [
            'attribute' => 'unidade_medida',
            'footer' => ''
        ];
    }

    if ($por_cliente) {
        $colunas[] = [
            'attribute' => 'cliente',
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


    echo GridView::widget([
        'showFooter' => true,
        'footerRowOptions' => ['style' => 'font-weight:bold;text-align:right;text-decoration: underline;'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $colunas
    ]);
    ?>


</div>
