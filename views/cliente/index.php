<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClienteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clientes';
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
    $(document).ready(function () {
        console.log('oi');
        $('.lock_codigo_app').click(function (event) {
            console.log($(this).attr('href'));
            $.post($(this).attr('href'), {'_csrf': '<?= Yii::$app->request->csrfToken ?>'})
                    .done(function (data) {
                        $('#divCodigoApp').html(data);
                    });
            event.preventDefault();
        });
    });
</script>
<div class="cliente-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<u>N</u>ovo Cliente', ['create'], ['class' => 'btn btn-success', 'accesskey' => 'n']) ?>
    </p>


    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'pk_cliente',
            'nome',
            'telefone',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Ações',
                'headerOptions' => ['style' => 'color:#337ab7'],
                'template' => '{update} {delete} {codigo_app}',
                'buttons' => [
                    'codigo_app' => function ($url, $model) {
                        return Html::a('<span data-toggle="modal" data-target="#modalCodigoApp" class="glyphicon glyphicon-lock"></span>', ['pegar-codigo-app', 'id' => $model->pk_cliente], [
                                    'title' => Yii::t('app', 'Código do App'), 'class' => 'lock_codigo_app', 'pk_cliente' => $model->pk_cliente,
                        ]);
                    },
                ],
            ],
        ],
    ]);
    ?>


</div>
<div type="button"  class="btn btn-default btn-pedido" data-toggle="modl" data-target="#modalCodigoApp" style='margin: 5px;text-align: left;max-width: 200px;min-height:100px; '>
</div>
<!-- Modal Código do Cliente -->
<div class="modal fade" id="modalCodigoApp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Código do App</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='divCodigoApp'>

            </div>            
        </div>
    </div>
</div>
