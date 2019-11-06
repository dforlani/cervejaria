<?php

use app\models\VendaSearch;
use kartik\field\FieldRange;
use kartik\widgets\SwitchInput;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel VendaSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Gráfico de Vendas de Litros';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    $(document).ready(function () {
        //não vai permitir que por_hora, por_dia e por_mes estajam ligados ao mesmo tempo
        $('#por_hora').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_mes').bootstrapSwitch('state', !data, true);
                $('#por_dia').bootstrapSwitch('state', !data, true);
            }

        });

        //não vai permitir que por_hora, por_dia e por_mes estajam ligados ao mesmo tempo
        $('#por_dia').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_mes').bootstrapSwitch('state', !data, true);
                $('#por_hora').bootstrapSwitch('state', !data, true);
            }

        });

        //não vai permitir que por_hora, por_dia e por_mes estajam ligados ao mesmo tempo
        $('#por_mes').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_dia').bootstrapSwitch('state', !data, true);
                $('#por_hora').bootstrapSwitch('state', !data, true);
            }

        });

        //não vai permitir que por_cliente e por_produto estejam selecionados ao mesmo tempo
        $('#por_cliente').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_produto').bootstrapSwitch('state', !data, true);
            }
        });

        //não vai permitir que por_cliente e por_produto estejam selecionados ao mesmo tempo
        $('#por_produto').on('switchChange.bootstrapSwitch', function (e, data) {
            if (data) {
                $('#por_cliente').bootstrapSwitch('state', !data, true);
            }
        });
    });
</script>


<div class="venda-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <form>
        <div class="row" style="height: 40px">
            <div class="col-md-2">

                <?php
                echo '<label class="control-label">Por hora</label>';
                echo SwitchInput::widget(['name' => 'por_hora', 'id' => 'por_hora', 'value' => $por_hora]);
                ?>
            </div>
            <div class="col-md-2">
                <?php
                echo '<label class="control-label">Por dia da semana</label>';
                echo SwitchInput::widget(['name' => 'por_dia', 'id' => 'por_dia', 'value' => $por_dia]);
                ?>
            </div>

            

            <div class="col-md-2">

                <?php
                echo '<label class="control-label">Por Produto</label>';
                echo SwitchInput::widget(['name' => 'por_produto', 'id' => 'por_produto', 'value' => $por_produto]);
                ?>

            </div>

            <div class="col-md-2">               
                <?php
                echo '<label class="control-label">Por Cliente</label>';
                echo SwitchInput::widget(['name' => 'por_cliente', 'id' => 'por_cliente', 'value' => $por_cliente]);
                ?>
            </div>


        </div>
        <div class="row">

            <div class="col-md-5" id='faixa_diaria'>
                <br>
                <?php
                echo FieldRange::widget([
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
        <br>
        <?= Html::submitButton('<u>G</u>erar', ['accesskey' => 'g', 'class' => 'btn btn-success']) ?>
    </form><br>




</div>

<div>


    <?php
  
    if (!empty($resultado)) {
        echo $this->render('_hora', ['resultado' => $resultado]);
    }
    ?>

</div>