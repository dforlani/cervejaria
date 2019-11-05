<?php

use yii\web\View;
?>
<?php
$this->registerJsFile(
        '@web/../vendor/nnnick/chartjs/dist/Chart.min.js', ['position' => View::POS_HEAD]
);
$this->registerJsFile(
        '@web/../vendor/nnnick/chartjs/samples/utils.js', ['position' => View::POS_HEAD]
);
?>






<style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    .chart-container {
        width: 900px;
        margin-left: 40px;
        margin-right: 40px;
        margin-bottom: 40px;
    }
    .container2 {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
</style>



<div class="container2">
</div>
<script>
    function createConfig(position) {
        return {
            type: 'line',
            data: {
                labels: <?= json_encode(array_keys($resultado))?>,
                datasets: [{
                        label: 'Por Hora',
                        borderColor: window.chartColors.red,
                        backgroundColor: window.chartColors.red,
                        data: <?= json_encode(array_values($resultado))?>,
                        fill: false
                    }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Venda de Litros'
                },
                tooltips: {
                    position: position,
                    mode: 'index',
                    intersect: false,
                },
            }
        };
    }

    window.onload = function () {
        var container = document.querySelector('.container2');

        ['average'].forEach(function (position) {
            var div = document.createElement('div');
            div.classList.add('chart-container');

            var canvas = document.createElement('canvas');
            div.appendChild(canvas);
            container.appendChild(div);

            var ctx = canvas.getContext('2d');
            var config = createConfig(position);
            new Chart(ctx, config);
        });
    };
</script>



