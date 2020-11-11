<?php

use yii\web\View;
?>
<?php
$this->registerJsFile(
        '@web/js/chartjs/Chart.min.js', ['position' => View::POS_HEAD]
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
        height: 500px;
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
    var randomColorGenerator = function () {
        return '#' + (Math.random().toString(16) + '0000000').slice(2, 8);
    };

    function createConfig(position) {
        return {
            type: 'line',
            data: {
                labels: <?= json_encode(array_keys($resultado[key($resultado)])) ?>,
                datasets: [
<?php
foreach ($resultado as $index => $agrupamentos) {
    echo "{
                        label: '$index',
                        borderColor: randomColorGenerator(), 
                        backgroundColor: randomColorGenerator(), 
                        data: " . json_encode(array_values($resultado[$index])) . ",
                        fill: false,
                        lineTension: 0
                    },";
}
?>
                ]
            },
            options: {
                maintainAspectRatio: false,
                
                responsive: true,
                title: {
                    display: true,
                    text: 'Venda de Litros de Cerveja'
                },
                tooltips: {
                    position: position,
                    mode: 'nearest',
                    intersect: false,
                },
                legend: {
                    position: 'bottom',
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



