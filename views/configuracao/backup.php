<?php

use app\models\Configuracao;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $configuracoes Configuracao */


$this->title = 'Configuração';
$this->params['breadcrumbs'][] = $this->title;
?>
<b>
<?= $msg?></b>


<form>
  
    <?= Html::submitButton('Gerar Backup', ['name' => 'gerar', 'value' => '1', 'class' => 'btn btn-primary']) ?>

     <?= Html::submitButton('Abrir Pasta', ['name' => 'abrir_pasta', 'value' => '1', 'class' => 'btn btn-success']) ?>
</form>

