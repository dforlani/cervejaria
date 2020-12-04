<?php

use app\models\CaixaSearch;
use lo\widgets\modal\ModalAjax;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel CaixaSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Caixa Aberto';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caixa-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php if (!empty($caixa)) { ?>
        <h3>Abertura:<?= Yii::$app->formatter->asDatetime($caixa->dt_abertura) ?></h3>
        <?php
        echo ModalAjax::widget([
            'id' => 'createMovimento',
            'header' => 'Adicionar Movimento',
            'toggleButton' => [
                'label' => 'Adicionar Movimento',
                'class' => 'btn btn-primary pull-left'
            ],
            'url' => Url::toRoute(['create', 'fk_caixa' => $caixa->pk_caixa]), // Ajax view with form to load
            'ajaxSubmit' => true, // Submit the contained form as ajax, true by default
            'size' => ModalAjax::SIZE_LARGE,
            'options' => ['class' => 'header-primary'],
            'autoClose' => true,
            'pjaxContainer' => '#grid-company-pjax',
        ]);
        $form = ActiveForm::begin(['method' => 'POST']);
        echo Html::submitButton('Fechar Caixa', ['name' => 'fechar_caixa', 'value' => '1', 'class' => 'btn btn-danger', 'style' => 'margin-left:20px']);
        ActiveForm::end();

        echo '<br><br>';


        echo ModalAjax::widget([
            'id' => 'createMovimento',
            'selector' => '.update', // all buttons in grid view with .update class
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
        <?=
        $this->render('_item_movimento', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'caixa' => $caixa,
            'visualizar' => false
        ]);
        ?>

        <?php
    } else {
        echo $this->render('_abrir_caixa', ['model' => $model]);
    }
    ?>


</div>
