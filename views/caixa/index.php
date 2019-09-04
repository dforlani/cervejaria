<?php

use app\components\Somatorio;
use app\models\CaixaSearch;
use lo\widgets\modal\ModalAjax;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel CaixaSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Caixa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caixa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php //echo Html::a('Criar Movimento', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
        echo ModalAjax::widget([
            'id' => 'createCompany',
            'header' => 'Adicionar Movimento',
            'toggleButton' => [
                'label' => 'Adicionar Movimento',
                'class' => 'btn btn-primary pull-left'
            ],
            'url' => Url::to(['create']), // Ajax view with form to load
            'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
            'size' => ModalAjax::SIZE_LARGE,
            'options' => ['class' => 'header-primary'],
            'autoClose' => true,
            'pjaxContainer' => '#grid-company-pjax',
        ]);
        echo '<br><br>';


        echo ModalAjax::widget([
            'id' => 'updateCompany',
            'selector' => '.update', // all buttons in grid view with href attribute
            'options' => ['class' => 'header-primary'],
            'pjaxContainer' => '#grid-company-pjax',
            'events' => [
                ModalAjax::EVENT_MODAL_SHOW => new JsExpression("
            function(event, data, status, xhr, selector) {
                selector.addClass('warning');
            }
       "),
                ModalAjax::EVENT_MODAL_SUBMIT => new JsExpression("
            function(event, data, status, xhr, selector) {
             
                if(status){
                     location.reload();
                    if(selector.data('scenario') == 'hello'){
                       // alert('hello');
                    } else {
                       // alert('goodbye');
                    }
                    $(this).modal('toggle');
                }
            }
        "),
                ModalAjax::EVENT_MODAL_SHOW_COMPLETE => new JsExpression("
            function(event, xhr, textStatus) {
                if (xhr.status == 403) {
                    $(this).modal('toggle');
                    alert('You do not have permission to execute this action');
                }
            }
        "),
                ModalAjax::EVENT_MODAL_SUBMIT_COMPLETE => new JsExpression("
            function(event, xhr, textStatus) {
                if (xhr.status == 403) {
                    $(this).modal('toggle');
                    alert('You do not have permission to execute this action');
                }
            }
        ")
            ]
        ]);
        ?>
    </p>    

    <?php
    Pjax::begin([
        'id' => 'grid-company-pjax',
        'timeout' => 5000,
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'showFooter' => true,
        'footerRowOptions' => ['style' => 'font-weight:bold;text-align:right;text-decoration: underline;'],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'valor',
                'format'=>'currency',
                'footer' => Yii::$app->formatter->asCurrency(Somatorio::getTotal($dataProvider->models, 'valor')),
                'contentOptions' => ['style' => 'text-align:right'],
            ],
            'tipo',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->pk_caixa], [
                                    'class' => 'update',
                                    'title' => 'Atualizar',
                        ]);
                    },
                ],],
        ],
    ]);
    Pjax::end();
    ?>


</div>
