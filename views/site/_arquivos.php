

<?php

use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $arquivosProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'arquivo',
        ['class' => 'yii\grid\ActionColumn',
            'contentOptions' => ['style' => 'width:84px; font-size:18px;']],
    ],
]);

$form = ActiveForm::begin([
            'id' => 'upload-form',
            'options' => ['enctype' => "multipart/form-data",],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]);

echo FileInput::widget([
    'name' => 'UploadForm[files]',
    'options' => ['multiple' => true,
    ],
    'pluginOptions' => ['hideThumbnailContent' => true]
]);

ActiveForm::end();
