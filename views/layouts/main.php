<?php
/* @var $this View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use kartik\widgets\AlertBlock;
use kartik\widgets\SideNav;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="content">
            <div class="container">                
                <div class="row">
                    <div class="col-md-2">
                        <?php
                        echo SideNav::widget([
                            'type' => SideNav::TYPE_DEFAULT,
                            'heading' => 'Menu',
                            'items' => [
                                ['label' => '', 'template' => '<a href="{url}"  accesskey="b">{icon}<u>B</u>alcão{label}</a>', 'url' => ['/venda/venda'],],
                                ['label' => '', 'template' => '<a href="{url}"  accesskey="h">{icon}Fol<u>h</u>a de Vendas{label}</a>', 'url' => ['/venda/folha'],],
                                //['label' => 'Caixa', 'url' => ['/caixa']],
                                ['options'=>['style'=>'background-color:#ddd;margin-top: 0px;']],
                                
                                ['label' => 'Todas as Vendas', 'url' => ['/venda']],                                
                                ['label' => '', 'template' => '<a href="{url}"  accesskey="t">{icon}Clien<u>t</u>es{label}</a>', 'url' => ['/cliente']],
                                ['label' => 'Comandas', 'url' => ['/comanda']],                                
                                ['label' => 'Produtos', 'url' => ['/produto']],
                                ['label' => 'Unidades de Medida', 'url' => ['/unidade-medida']],                                
                                ['options'=>['style'=>'background-color:#ddd;margin-top: 0px;']],
                                
                                ['label' => 'Relatórios', 'items' => [['label' => 'Vendas', 'url' => ['/relatorio/vendas']]]],
                                ['options'=>['style'=>'background-color:#ddd;margin-top: 0px;']],
                                
                                ['label' => 'Configuração', 'url' => ['/configuracao']],
                                ['options'=>['style'=>'background-color:#ddd;margin-top: 0px;']],
                                
                                ['label' => '1) Atualizar', 'template' => '<a href="{url}"  target="_blank">{icon}{label}</a>', 'url' => ['/update/atualizar']],
                                ['label' => '2) Atualizar Banco', 'template' => '<a href="{url}"  target="_blank">{icon}{label}</a>', 'url' => ['/update/atualizar-banco']],
                            ],]
                        );
                        ?>
                        
                    </div>
                    <div class="col-md-10">
                        <div class="panel panel-default">

                            <?=
                            Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ])
                            ?>
                            <?=
                            AlertBlock::widget([
                                'useSessionFlash' => true,
                                'type' => AlertBlock::TYPE_ALERT,
                                'delay' => false,
                            ]);
                            ?>
                            <?php // Alert::widget() ?>
                            <?= $content ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
