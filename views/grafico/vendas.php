<?php

use app\models\VendaSearch;
use kartik\field\FieldRange;
use kartik\tabs\TabsX;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel VendaSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Gráfico de Vendas de Cerveja';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    $(document).ready(function () {

        //não vai permitir que por_hora, por_dia_semana e por_mes_agregado estajam ligados ao mesmo tempo
        $('#por_gasto').on('switchChange.bootstrapSwitch', function (e, data) {
            if (($('#por_gasto').bootstrapSwitch('state') == false) && ($('#por_litro').bootstrapSwitch('state') == false)) {
                $('#por_litro').bootstrapSwitch('state', !data, true);
            }
        });

        //não vai permitir que por_hora, por_dia_semana e por_mes_agregado estajam ligados ao mesmo tempo
        $('#por_litro').on('switchChange.bootstrapSwitch', function (e, data) {
            if (($('#por_gasto').bootstrapSwitch('state') == false) && ($('#por_litro').bootstrapSwitch('state') == false)) {
                $('#por_gasto').bootstrapSwitch('state', !data, true);
            }
        });



        /**
         * Ao menos um deve ficar selecionado
         * @param {type} data
         * @returns {undefined}
         */
        function verificaPorPeriodoSelecionado(data) {
            if (($('#por_dia').bootstrapSwitch('state') == false)
                    && ($('#por_mes').bootstrapSwitch('state') == false)
                    && ($('#por_mes_agregado').bootstrapSwitch('state') == false)
                    && ($('#por_dia_semana').bootstrapSwitch('state') == false)
                    && ($('#por_hora').bootstrapSwitch('state') == false)
                    ) {
                $('#por_dia').bootstrapSwitch('state', !data, true);
            }
        }

        //não vai permitir que por_hora, por_dia_semana e por_mes_agregado estajam ligados ao mesmo tempo
        $('#por_hora').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_mes').bootstrapSwitch('state', !data, true);
                $('#por_mes_agregado').bootstrapSwitch('state', !data, true);
                $('#por_dia_semana').bootstrapSwitch('state', !data, true);
                $('#por_dia').bootstrapSwitch('state', !data, true);
            }
            verificaPorPeriodoSelecionado(data);

        });
        //não vai permitir que por_hora, por_dia_semana e por_mes_agregado estajam ligados ao mesmo tempo
        $('#por_dia_semana').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_mes').bootstrapSwitch('state', !data, true);
                $('#por_mes_agregado').bootstrapSwitch('state', !data, true);
                $('#por_hora').bootstrapSwitch('state', !data, true);
                $('#por_dia').bootstrapSwitch('state', !data, true);
            }
            verificaPorPeriodoSelecionado(data);

        });
        //não vai permitir que por_hora, por_dia_semana e por_mes_agregado estajam ligados ao mesmo tempo
        $('#por_dia').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_mes').bootstrapSwitch('state', !data, true);
                $('#por_mes_agregado').bootstrapSwitch('state', !data, true);
                $('#por_hora').bootstrapSwitch('state', !data, true);
                $('#por_dia_semana').bootstrapSwitch('state', !data, true);
            }
            verificaPorPeriodoSelecionado(data);

        });
        //não vai permitir que por_hora, por_dia_semana e por_mes estajam ligados ao mesmo tempo
        $('#por_mes_agregado').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_mes').bootstrapSwitch('state', !data, true);
                $('#por_dia_semana').bootstrapSwitch('state', !data, true);
                $('#por_hora').bootstrapSwitch('state', !data, true);
                $('#por_dia').bootstrapSwitch('state', !data, true);
            }
            verificaPorPeriodoSelecionado(data);
        });
        $('#por_mes').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_mes_agregado').bootstrapSwitch('state', !data, true);
                $('#por_dia_semana').bootstrapSwitch('state', !data, true);
                $('#por_hora').bootstrapSwitch('state', !data, true);
                $('#por_dia').bootstrapSwitch('state', !data, true);
            }
            verificaPorPeriodoSelecionado(data);
        });


        //não vai permitir que por_cliente e por_produto e forma de venda estejam selecionados ao mesmo tempo que produto
        $('#por_cliente').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_produto').bootstrapSwitch('state', !data, false);
                $('#por_forma_venda').bootstrapSwitch('state', !data, false);
            }
            verificaPorTipoAMostrar(data);
        });
        //não vai permitir que por_cliente e por_produto e forma de venda estejam selecionados ao mesmo tempo que produto
        $('#por_produto').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_cliente').bootstrapSwitch('state', !data, false);
                $('#por_forma_venda').bootstrapSwitch('state', !data, false);
            }
            verificaPorTipoAMostrar(data);
        });

        //não vai permitir que por_cliente e por_produto e forma de venda estejam selecionados ao mesmo tempo que produto
        $('#por_forma_venda').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_cliente').bootstrapSwitch('state', !data, false);
                $('#por_produto').bootstrapSwitch('state', !data, false);
            }
            verificaPorTipoAMostrar(data);
        });

        function verificaPorTipoAMostrar(data) {
            if (($('#por_cliente').bootstrapSwitch('state') == false)
                    && ($('#por_produto').bootstrapSwitch('state') == false)
                    && ($('#por_forma_venda').bootstrapSwitch('state') == false)
                    ) {
                $('#por_produto').bootstrapSwitch('state', !data, true);
            }
        }
    });
</script>


<div class="venda-index" style="padding: 10px">
    <h1><?= Html::encode($this->title) ?></h1>
    <form>
        <div class="row" style="padding: 20px">
            <div class="col-md-2">

                <?php
                echo '<label class="control-label">Apenas Cervejas</label>';
                echo SwitchInput::widget(['name' => 'apenas_cervejas_ativas', 'id' => 'apenas_cervejas_ativas', 'value' => $apenas_cervejas_ativas]);
                ?>
            </div>
            <div class="col-md-4">
                <div class="row" style="margin-right: 10px;border-bottom: 5px solid blue;">
                    <div class="col-md-6" >
                        <br>
                        <?php
                        echo '<label class="control-label">Por Gasto</label>';
                        echo SwitchInput::widget(['name' => 'por_gasto', 'id' => 'por_gasto', 'value' => $por_gasto]);
                        ?>
                    </div>

                    <div class="col-md-6">
                        <br>
                        <?php
                        echo '<label class="control-label">Por Litro</label>';
                        echo SwitchInput::widget(['name' => 'por_litro', 'id' => 'por_litro', 'value' => $por_litro]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row"  style="border-bottom: 5px solid red;">
                    <div class="col-md-4">  
                        <br>
                        <?php
                        echo '<label class="control-label">Por Produto</label>';
                        echo SwitchInput::widget(['name' => 'por_produto', 'id' => 'por_produto', 'value' => $por_produto]);
                        ?>

                    </div>

                    <div class="col-md-4">
                        <?php
                        echo '<label class="control-label">Por Produto e Forma de Venda</label>';
                        echo SwitchInput::widget(['name' => 'por_forma_venda', 'id' => 'por_forma_venda', 'value' => $por_forma_venda]);
                        ?>
                    </div>

                    <div class="col-md-4">               
                        <br>
                        <?php
                        echo '<label class="control-label">Por Cliente</label>';
                        echo SwitchInput::widget(['name' => 'por_cliente', 'id' => 'por_cliente', 'value' => $por_cliente]);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 20px" class="row">
            <div class="col-md-5" id='faixa_diaria'>
                <?php
                echo FieldRange::widget([
                    'label' => 'Faixa de tempo',
                    'name1' => 'data_inicial',
                    'value1' => $data_inicial,
                    'name2' => 'data_final',
                    'value2' => $data_final,
                    'separator' => ' ← até →',
                    'type' => FieldRange::INPUT_DATE,
                ]);
                ?>
            </div>

            <div class="col-md-3">
                <label class="control-label">Filtrar Produtos</label>
                <?php
                echo Select2::widget([
                    'name' => 'cervejas_selecionadas',
                    'data' => $cervejas,
                    'value' => $cervejas_selecionadas,
                    'options' => [
                        'placeholder' => 'Selecione os produtos ...',
                        'multiple' => true
                    ],
                ]);
                ?>
            </div>




        </div>
        <div class="row" style="padding: 20px;">
            <div class='col-md-12' style="margin-right: 10px" >
                <div class="row" style="border-bottom: 5px solid red">
                    <div class="col-md-2">
                        <br>
                        <?php
                        echo '<label class="control-label">Por dia</label>';
                        echo SwitchInput::widget(['name' => 'por_dia', 'id' => 'por_dia', 'value' => $por_dia]);
                        ?>
                    </div>



                    <div class="col-md-2">
                        <br>
                        <?php
                        echo '<label class="control-label">Por Mês</label>';
                        echo SwitchInput::widget(['name' => 'por_mes', 'id' => 'por_mes', 'value' => $por_mes]);
                        ?>
                    </div>

                    <div class="col-md-2" >
                        <?php
                        echo '<label class="control-label">Consolidado hora</label>';
                        echo SwitchInput::widget(['name' => 'por_hora', 'id' => 'por_hora', 'value' => $por_hora]);
                        ?>
                    </div>


                    <div class="col-md-3">
                        <br>
                        <?php
                        echo '<label class="control-label">Consolidado dia da semana</label>';
                        echo SwitchInput::widget(['name' => 'por_dia_semana', 'id' => 'por_dia_semana', 'value' => $por_dia_semana]);
                        ?>
                    </div>

                    <div class="col-md-3">
                        <br>
                        <?php
                        echo '<label class="control-label">Consolidado Mês</label>';
                        echo SwitchInput::widget(['name' => 'por_mes_agregado', 'id' => 'por_mes_agregado', 'value' => $por_mes_agregado]);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <?= Html::submitButton('<u>G</u>erar', ['accesskey' => 'g', 'class' => 'btn btn-success']) ?>
    </form>
</div>




</div>

<div>
    <?php
    $grafico = "";
    if (!empty($graficoResultado)) {
        echo $this->render('_grafico', ['graficoResultado' => $graficoResultado]);
    }
    ?>

</div>